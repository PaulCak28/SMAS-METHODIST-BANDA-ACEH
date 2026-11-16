<?php

use App\Models\ExamSession;
use App\Models\ExamQuestion;
use App\Services\ExamGrader;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Schema::create('exam_answers', function (Blueprint $table) {
        $table->unsignedBigInteger('exam_id');
        $table->string('students_id');
        $table->unsignedBigInteger('question_id');
        $table->decimal('score', 6, 2)->nullable();
    });
});

afterEach(function () {
    Schema::dropIfExists('exam_answers');
});

it('uses the teacher configured points as the final score, capped at 100', function () {
    DB::table('exam_answers')->insert([
        ['exam_id' => 1, 'students_id' => 'S1', 'question_id' => 1, 'score' => 7.50],
        ['exam_id' => 1, 'students_id' => 'S1', 'question_id' => 2, 'score' => 2.25],
        ['exam_id' => 1, 'students_id' => 'S2', 'question_id' => 1, 'score' => 95],
        ['exam_id' => 2, 'students_id' => 'S1', 'question_id' => 1, 'score' => 95],
    ]);

    $session = new ExamSession;
    $session->exam_id = 1;
    $session->student_id = 'S1';

    expect(app(ExamGrader::class)->finalScore($session))->toBe(9.75);

    DB::table('exam_answers')->insert([
        'exam_id' => 1,
        'students_id' => 'S1',
        'question_id' => 3,
        'score' => 95,
    ]);

    expect(app(ExamGrader::class)->finalScore($session))->toBe(100.0);
});

it('distributes exam points evenly across questions', function () {
    expect(ExamGrader::equalQuestionWeights(2))->toBe([50.0, 50.0])
        ->and(array_sum(ExamGrader::equalQuestionWeights(3)))->toBe(100.0)
        ->and(ExamGrader::equalQuestionWeights(0))->toBe([]);
});

it('grades the selected multiple-choice option by its key or letter', function () {
    $question = new ExamQuestion([
        'question_type' => 'pilihan_ganda',
        'options' => ['Opsi A', 'Opsi B'],
        'correct_answer' => 0,
        'score_weight' => 50,
    ]);
    $grader = app(ExamGrader::class);

    expect($grader->gradeQuestion($question, '0'))->toBe([50.0, true])
        ->and($grader->gradeQuestion($question, 'A'))->toBe([50.0, true])
        ->and($grader->gradeQuestion($question, '1'))->toBe([0.0, false]);
});
