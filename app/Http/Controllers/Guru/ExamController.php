<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSession;
use App\Services\Access;
use App\Services\ExamGrader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    private function own(Request $request, Exam $exam): void
    {
        if (!$exam->classSubjectTeacher) {
            abort(404, 'Ujian ini tidak memiliki data pengampu kelas (cst_id kosong). Silakan hapus atau perbaiki data ujian ini.');
        }

        Access::authorizeTeacher($request->user(), $exam->classSubjectTeacher);
    }

    public function index(Request $request)
    {
        $exams = Exam::with('classSubjectTeacher.subject', 'classSubjectTeacher.schoolClass')
            ->withCount('questions', 'sessions')
            ->whereHas('classSubjectTeacher', fn ($q) => $q->where('teacher_id', $request->user()->teacher->nip))
            ->latest()->paginate(15);

        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')
            ->where('teacher_id', $request->user()->teacher->nip)
            ->orderBy('class_id')
            ->get();

        return view('guru.exams.index', compact('exams', 'csts'));
    }

    public function create(Request $request)
    {
        $csts = ClassSubjectTeacher::with('subject', 'schoolClass')
            ->where('teacher_id', $request->user()->teacher->nip)
            ->orderBy('class_id')
            ->get();

        return view('guru.exams.create', compact('csts'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'cst_id' => 'required|exists:class_subject_teacher,cst_id',
            'title' => 'required|string|max:150',
            'exam_type' => ['required', Rule::in(['uts', 'uas', 'kuis'])],
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'pass_score' => 'required|numeric|min:0|max:100',
            'instructions' => 'nullable|string',
        ]);

        Access::authorizeTeacher($request->user(), ClassSubjectTeacher::findOrFail($v['cst_id']));

        $exam = Exam::create($v + [
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'shuffle_options' => $request->boolean('shuffle_options'),
            'is_published' => false,
        ]);

        return redirect()->route('guru.exams.show', $exam)
            ->with('success', 'Ujian dibuat. Silakan isi soal beserta kunci jawaban.');
    }

    public function show(Request $request, Exam $exam)
    {
        $this->own($request, $exam);
        $exam->load('classSubjectTeacher.subject', 'classSubjectTeacher.schoolClass', 'questions');
        $this->syncQuestionWeights($exam);
        $exam->load('questions');

        return view('guru.exams.show', compact('exam'));
    }

    public function edit(Request $request, Exam $exam)
    {
        $this->own($request, $exam);

        return view('guru.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->own($request, $exam);
        $v = $request->validate([
            'title' => 'required|string|max:150',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'pass_score' => 'required|numeric|min:0|max:100',
            'instructions' => 'nullable|string',
        ]);

        $exam->update($v + [
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'shuffle_options' => $request->boolean('shuffle_options'),
        ]);

        return redirect()
            ->route('guru.exams.index')
            ->with('success', 'Ujian berhasil diedit.');
    }

    public function destroy(Request $request, Exam $exam)
    {
        $this->own($request, $exam);
        $exam->delete();

        return redirect()->route('guru.exams.index')->with('success', 'Ujian dihapus.');
    }

    public function togglePublish(Request $request, Exam $exam)
    {
        $this->own($request, $exam);
        if (! $exam->is_published && ! $exam->isComplete()) {
            return back()->with('error', 'Ujian belum punya soal. Tambahkan soal terlebih dahulu.');
        }
        $exam->update(['is_published' => ! $exam->is_published]);

        return back()->with('success', $exam->is_published ? 'Ujian dipublikasikan ke siswa.' : 'Ujian ditarik dari siswa.');
    }

    // ---------- Soal (Memakai sp_create_exam_question) ----------
    public function storeQuestion(Request $request, Exam $exam)
    {
        $this->own($request, $exam);
        $data = $this->parseQuestion($request);
        if (is_string($data)) {
            return back()->withInput()->with('error', $data);
        }

        $questionCount = DB::connection('mysql_guru')
            ->table('exam_questions')
            ->where('exam_id', $exam->exam_id)
            ->count();
        $data['score_weight'] = 100 / ($questionCount + 1);

        DB::connection('mysql_guru')->statement(
            'CALL sp_create_exam_question(?, ?, ?, ?, ?, ?, ?)',
            [
                $exam->exam_id,
                $request->user()->teacher->nip,
                $data['question_type'],
                $data['question_text'],
                $data['options'] !== null ? json_encode($data['options']) : null,
                $data['correct_answer'] !== null ? json_encode($data['correct_answer']) : null,
                $data['score_weight'],
            ]
        );
        $this->syncQuestionWeights($exam);

        return back()->with('success', 'Soal ditambahkan.');
    }

    public function updateQuestion(Request $request, Exam $exam, ExamQuestion $question)
    {
        $this->own($request, $exam);
        abort_unless($question->exam_id === $exam->exam_id, 404);

        $data = $this->parseQuestion($request);
        if (is_string($data)) {
            return back()->withInput()->with('error', $data);
        }

        $data['score_weight'] = $question->score_weight;
        $question->update($data);
        $this->syncQuestionWeights($exam);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function updateScore(Request $request, Exam $exam, ExamQuestion $question)
    {
        $this->own($request, $exam);
        abort_unless($question->exam_id === $exam->exam_id, 404);

        $request->validate([
            'score_weight' => 'required|numeric|min:0|max:100',
        ]);

        $question->update(['score_weight' => $request->score_weight]);

        return back()->with('success', 'Bobot soal berhasil diperbarui.');
    }

    public function destroyQuestion(Request $request, Exam $exam, ExamQuestion $question)
    {
        $this->own($request, $exam);
        abort_unless($question->exam_id === $exam->exam_id, 404);
        $question->delete();
        $this->syncQuestionWeights($exam);

        return back()->with('success', 'Soal dihapus.');
    }

    private function syncQuestionWeights(Exam $exam): bool
    {
        $connection = DB::connection('mysql_guru');
        $questionIds = $connection->table('exam_questions')
            ->where('exam_id', $exam->exam_id)
            ->orderBy('exam_question_id')
            ->pluck('exam_question_id')
            ->all();
        $weights = ExamGrader::equalQuestionWeights(count($questionIds));
        $changed = false;

        foreach ($questionIds as $index => $questionId) {
            $weight = $weights[$index];
            $currentWeight = (float) $connection->table('exam_questions')
                ->where('exam_question_id', $questionId)
                ->value('score_weight');

            if ($currentWeight !== $weight) {
                $connection->table('exam_questions')
                    ->where('exam_question_id', $questionId)
                    ->update(['score_weight' => $weight]);
                $changed = true;
            }
        }

        return $changed;
    }

    private function parseQuestion(Request $request): array|string
    {
        $request->validate([
            'question_type' => ['required', Rule::in(array_keys(config('school.question_types')))],
            'question_text' => 'required|string',
            'score_weight' => 'nullable|numeric|min:0|max:100',
        ]);
        $type = $request->input('question_type');
        $lines = fn (?string $s) => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $s)), 'strlen'));

        $options = null;
        $key = null;
        switch ($type) {
            case 'pilihan_ganda':
                $options = $lines($request->input('options'));
                $key = $request->filled('key_single') ? (int) $request->input('key_single') - 1 : null;
                break;
            case 'pilihan_ganda_kompleks':
                $options = $lines($request->input('options'));
                $key = array_values(array_unique(array_map(fn ($n) => (int) trim($n) - 1, array_filter(explode(',', (string) $request->input('key_multi')), 'strlen'))));
                break;
            case 'benar_salah':
                $options = [];
                $key = [];
                foreach ($lines($request->input('options')) as $line) {
                    [$text, $flag] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
                    $options[] = $text;
                    $key[] = in_array(strtoupper($flag), ['B', 'BENAR', 'TRUE'], true);
                }
                break;
            case 'menjodohkan':
                $options = ['left' => [], 'right' => $lines($request->input('pair_right'))];
                $key = [];
                foreach ($lines($request->input('pair_left')) as $i => $line) {
                    [$text, $num] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '0');
                    $options['left'][] = $text;
                    $key[(string) $i] = (int) $num - 1;
                }
                break;
        }

        if ($err = ExamGrader::validateKey($type, $options, $key)) {
            return $err;
        }

        return [
            'question_text' => $request->input('question_text'),
            'question_type' => $type,
            'options' => $options,
            'correct_answer' => $key,
            'score_weight' => $request->filled('score_weight')
                ? (float) $request->input('score_weight')
                : 100,
        ];
    }

    // ---------- Hasil & koreksi esai (Memakai View & Stored Procedure) ----------
    public function results(Request $request, Exam $exam, ExamGrader $grader)
    {
        $this->own($request, $exam);

        // Tutup sesi yang waktunya habis
        $exam->sessions()->where('status', 'berlangsung')->with('exam')->get()
            ->filter(fn ($s) => now()->gte($s->deadline()))
            ->each(fn ($s) => $grader->gradeSession($s, 'mysql_guru'));

        $exam->load('classSubjectTeacher.subject', 'classSubjectTeacher.schoolClass.students.user', 'questions');
        $weightsChanged = $this->syncQuestionWeights($exam);
        $exam->load('questions');

        // Perbaikan: keyBy menggunakan 'student_id' (bukan 'students_id')
        $sessions = $exam->sessions()->with('student.user')->withCount('violations')->get()->keyBy('student_id');
        if ($weightsChanged) {
            foreach ($sessions->whereIn('status', ['selesai', 'keluar']) as $session) {
                $grader->gradeAnswers($session);
                DB::connection('mysql_guru')->table('grades')
                    ->where('exam_id', $exam->exam_id)
                    ->where('students_id', $session->student_id)
                    ->update([
                        'score' => $grader->finalScore($session),
                        'updated_at' => now(),
                    ]);
            }
        }

        // Data tambahan dari Database View (untuk ringkasan nilai & statistik ujian)
        $examResultsView = DB::connection('mysql_guru')
            ->table('v_exam_results')
            ->where('exam_id', $exam->exam_id)
            ->get()
            ->keyBy('exam_session_id');

        $examStats = DB::connection('mysql_guru')
            ->table('v_exam_statistics')
            ->where('exam_id', $exam->exam_id)
            ->first();

        $pendingEssays = DB::connection('mysql_guru')
            ->table('exam_answers as ea')
            ->join('exam_questions as q', 'q.exam_question_id', '=', 'ea.question_id')
            ->join('exam_sessions as es', function ($join) {
                $join->on('es.exam_id', '=', 'ea.exam_id')
                    ->on('es.student_id', '=', 'ea.students_id');
            })
            ->where('q.exam_id', $exam->exam_id)
            ->where('q.question_type', 'esai')
            ->whereIn('es.status', ['selesai', 'keluar'])
            ->whereNull('ea.graded_at')
            ->select('ea.exam_answer_id')
            ->get();

        $violationSummaries = DB::connection('mysql_guru')
            ->table('v_violation_summary')
            ->where('exam_id', $exam->exam_id)
            ->get()
            ->groupBy('exam_session_id');

        return view('guru.exams.results', compact(
            'exam',
            'sessions',
            'examResultsView',
            'examStats',
            'pendingEssays',
            'violationSummaries'
        ));
    }

    public function session(Request $request, ExamSession $session)
    {
        $exam = $session->exam()->with('questions', 'classSubjectTeacher.subject')->first();
        Access::authorizeTeacher($request->user(), $exam->classSubjectTeacher);
        $session->load('student.user', 'violations');

        // Perbaikan: $session->student_id (di tabel exam_answers tetap 'students_id')
        $answers = $exam->answers()->where('students_id', $session->student_id)->get()->keyBy('question_id');

        return view('guru.exams.session', compact('session', 'exam', 'answers'));
    }

    /** Nilai esai disimpan via Stored Procedure sp_grade_exam_essay & sinkron ke tabel grades */
    public function gradeEssay(Request $request, ExamSession $session)
    {
        $exam = $session->exam()->with('classSubjectTeacher')->first();
        Access::authorizeTeacher($request->user(), $exam->classSubjectTeacher);

        $v = $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $essayIds = $exam->questions()->where('question_type', 'esai')->pluck('exam_question_id')->all();
        $teacherNip = $request->user()->teacher->nip;

        foreach ($v['scores'] as $questionId => $score) {
            if ($score === null || ! in_array((int) $questionId, $essayIds, true)) {
                continue;
            }

            DB::connection('mysql_guru')->select(
                'CALL sp_grade_exam_essay(?, ?, ?, ?)',
                [
                    $session->exam_session_id,
                    (int) $questionId,
                    $teacherNip,
                    (float) $score,
                ]
            );
        }

        return back()->with('success', 'Nilai esai tersimpan dan nilai akhir ujian berhasil disinkronkan.');
    }

    /** Hentikan paksa sesi ujian siswa via Stored Procedure sp_force_finish_session */
    public function forceFinish(Request $request, ExamSession $session)
    {
        $exam = $session->exam()->with('classSubjectTeacher')->first();
        Access::authorizeTeacher($request->user(), $exam->classSubjectTeacher);

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        DB::connection('mysql_guru')->select(
            'CALL sp_force_finish_session(?, ?, ?)',
            [
                $session->exam_session_id,
                $request->user()->teacher->nip,
                $request->input('reason', 'Dihentikan paksa oleh guru'),
            ]
        );

        return back()->with('success', 'Sesi ujian siswa berhasil dihentikan paksa dan nilai telah direkam.');
    }
}