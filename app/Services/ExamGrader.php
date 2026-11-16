<?php

namespace App\Services;

use App\Models\ExamQuestion;
use App\Models\ExamSession;
use Illuminate\Support\Facades\DB;

/**
 * Koreksi otomatis berdasarkan kunci jawaban yang dimasukkan guru.
 * Esai dinilai manual; bobotnya menjadi poin maksimum yang dapat diberikan guru.
 */
class ExamGrader
{
    /** @return array{0: float, 1: bool|null} [skor, benar-penuh?] */
    public function gradeQuestion(ExamQuestion $q, mixed $answer): array
    {
        $weight = (float) $q->score_weight;
        $key = $q->correct_answer;

        if ($q->isEssay() || $answer === null || $answer === '' || $answer === []) {
            return [0.0, $q->isEssay() ? null : false];
        }

        switch ($q->question_type) {
            case 'pilihan_ganda':
                $optionKeys = array_keys((array) $q->options);
                $optionKeyValues = array_map('strval', $optionKeys);
                $answerIndex = array_search((string) $answer, $optionKeyValues, true);
                $correctIndex = array_search((string) $key, $optionKeyValues, true);

                if ($answerIndex === false && is_numeric($answer)) {
                    $answerIndex = (int) $answer;
                }
                if ($correctIndex === false && is_numeric($key)) {
                    $correctIndex = (int) $key;
                }
                if ($answerIndex === false && is_string($answer) && strlen($answer) === 1 && ctype_alpha($answer)) {
                    $answerIndex = ord(strtoupper($answer)) - ord('A');
                }
                if ($correctIndex === false && is_string($key) && strlen($key) === 1 && ctype_alpha($key)) {
                    $correctIndex = ord(strtoupper($key)) - ord('A');
                }

                $optionCount = count($optionKeys);
                $ok = $answerIndex !== false
                    && $correctIndex !== false
                    && $answerIndex < $optionCount
                    && $correctIndex < $optionCount
                    && $answerIndex === $correctIndex;
                return [$ok ? $weight : 0.0, $ok];

            case 'pilihan_ganda_kompleks':
                $key = array_map('intval', (array) $key);
                $ans = array_map('intval', (array) $answer);
                if (empty($key)) {
                    return [0.0, false];
                }
                $right = count(array_intersect($ans, $key));
                $wrong = count(array_diff($ans, $key));
                $ratio = max(0, ($right - $wrong) / count($key));
                $full = $right === count($key) && $wrong === 0;
                return [round($weight * $ratio, 2), $full];

            case 'benar_salah':
                $key = array_values((array) $key);
                $ans = (array) $answer;
                if (empty($key)) {
                    return [0.0, false];
                }
                $match = 0;
                foreach ($key as $i => $k) {
                    if (isset($ans[$i]) && filter_var($ans[$i], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === (bool) $k) {
                        $match++;
                    }
                }
                return [round($weight * $match / count($key), 2), $match === count($key)];

            case 'menjodohkan':
                $key = (array) $key;
                $ans = (array) $answer;
                if (empty($key)) {
                    return [0.0, false];
                }
                $match = 0;
                foreach ($key as $left => $right) {
                    if (isset($ans[$left]) && (string) $ans[$left] === (string) $right) {
                        $match++;
                    }
                }
                return [round($weight * $match / count($key), 2), $match === count($key)];
        }

        return [0.0, false];
    }

    /**
     * Koreksi seluruh jawaban sesi. Skor dan is_correct ditulis ke exam_answers,
     * sesi hanya ditandai selesai (tidak ada lagi skor di exam_sessions).
     */
    public function gradeSession(ExamSession $session, string $connection = 'mysql_siswa'): ExamSession
    {
        $this->gradeAnswers($session);
        DB::connection($connection)->select('CALL sp_record_exam_grade(?)', [
            $session->exam_session_id,
        ]);

        return $session->fresh();
    }

    public function gradeAnswers(ExamSession $session): void
    {
        DB::transaction(function () use ($session) {
            $exam = $session->exam()->with('questions')->first();
            $answers = $exam->answers()->where('students_id', $session->student_id)->get()->keyBy('question_id');

            foreach ($exam->questions as $q) {
                if ($q->isEssay()) {
                    continue; // esai dinilai manual oleh guru
                }
                $row = $answers->get($q->exam_question_id);
                [$score, $ok] = $this->gradeQuestion($q, $row?->answer);

                if ($row) {
                    // exam_answers tanpa primary key tunggal: update lewat query
                    \App\Models\ExamAnswer::where('exam_id', $row->exam_id)
                        ->where('students_id', $row->students_id)
                        ->where('question_id', $row->question_id)
                        ->update(['score' => $score, 'is_correct' => $ok]);
                }
            }
        });
    }

    /** Jumlah poin jawaban objektif sesuai bobot soal, maksimal 100. */
    public function objectiveScore(ExamSession $session): float
    {
        $exam = $session->exam()->with('questions')->first();
        $earned = \App\Models\ExamAnswer::where('exam_id', $session->exam_id)
            ->where('students_id', $session->student_id)
            ->whereIn('question_id', $exam->questions->where('question_type', '!=', 'esai')->pluck('exam_question_id'))
            ->sum('score');

        return min(100, (float) $earned);
    }

    /** Jumlah poin seluruh jawaban sesuai bobot soal, maksimal 100. */
    public function finalScore(ExamSession $session): float
    {
        $earned = \App\Models\ExamAnswer::where('exam_id', $session->exam_id)
            ->where('students_id', $session->student_id)
            ->sum('score');

        return min(100, (float) $earned);
    }

    /** Validasi: apakah kunci jawaban soal lengkap & masuk akal? */
    public static function validateKey(string $type, mixed $options, mixed $key): ?string
    {
        return match ($type) {
            'pilihan_ganda' => (is_array($options) && count($options) >= 2 && $key !== null && isset($options[(int) $key]))
                ? null : 'Pilihan ganda butuh minimal 2 opsi dan satu kunci jawaban.',
            'pilihan_ganda_kompleks' => (is_array($options) && count($options) >= 2 && is_array($key) && count($key) >= 1)
                ? null : 'Pilihan ganda kompleks butuh minimal 2 opsi dan minimal satu kunci.',
            'benar_salah' => (is_array($options) && count($options) >= 1 && is_array($key) && count($key) === count($options))
                ? null : 'Setiap pernyataan benar/salah wajib punya kunci.',
            'menjodohkan' => (is_array($options) && count($options['left'] ?? []) >= 2 && count($options['right'] ?? []) >= 2 && is_array($key) && count($key) === count($options['left']))
                ? null : 'Pasangan jawaban belum lengkap.',
            default => null,
        };
    }

    /** @return list<float> Evenly distributes 100 points, preserving two decimal places. */
    public static function equalQuestionWeights(int $questionCount): array
    {
        if ($questionCount <= 0) {
            return [];
        }

        $totalCents = 10000;
        $baseCents = intdiv($totalCents, $questionCount);
        $extraCents = $totalCents % $questionCount;

        return array_map(
            fn (int $index) => (float) ($baseCents + ($index < $extraCents ? 1 : 0)) / 100,
            range(0, $questionCount - 1)
        );
    }

    /** Masukkan nilai ujian ke buku nilai (dihitung sistem, graded_by = null). */
public function recordToGradebook(ExamSession $session): void
{
    \Illuminate\Support\Facades\DB::connection('mysql_siswa')->select('CALL sp_record_exam_grade(?)', [
        $session->exam_session_id,
    ]);
}

    /** Ringkasan untuk siswa/orang tua: benar, salah, tidak dijawab, nilai, lulus atau tidak. */
    public function summary(ExamSession $session): array
    {
        $exam = $session->exam()->with('questions')->first();
        $objective = $exam->questions->where('question_type', '!=', 'esai');
        $essays = $exam->questions->where('question_type', 'esai');

        $answers = \App\Models\ExamAnswer::where('exam_id', $session->exam_id)
            ->where('students_id', $session->student_id)
            ->whereIn('question_id', $objective->pluck('exam_question_id'))
            ->get();

        $correct = $answers->where('is_correct', true)->count();
        $wrong = $answers->filter(
            fn ($a) => $a->is_correct === false && $a->answer !== null && $a->answer !== [] && $a->answer !== ''
        )->count();
        $total = $objective->count();
        $score = $this->finalScore($session);

        $essayGraded = \App\Models\ExamAnswer::where('exam_id', $session->exam_id)
            ->where('students_id', $session->student_id)
            ->whereIn('question_id', $essays->pluck('exam_question_id'))
            ->whereNotNull('graded_at')
            ->count();

        return [
            'total' => $total,
            'correct' => $correct,
            'wrong' => $wrong,
            'unanswered' => max(0, $total - $correct - $wrong),
            'score' => $score,
            'pass_score' => (float) $exam->pass_score,
            'passed' => $score >= (float) $exam->pass_score,
            'essay_total' => $essays->count(),
            'essay_graded' => $essayGraded,
        ];
    }
}