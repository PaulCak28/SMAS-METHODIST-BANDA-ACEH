<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Charset + collation yang sama dengan kolom kunci (supaya tidak "Illegal mix of collations"). */
    private function cs(): string
    {
        $conn = config('database.connections.' . DB::getDefaultConnection());

        return 'CHARACTER SET ' . ($conn['charset'] ?? 'utf8mb4') . ' COLLATE ' . ($conn['collation'] ?? 'utf8mb4_unicode_ci');
    }

    public function up(): void
    {
        $cs = $this->cs();

        // 1. Angka -> predikat huruf
        DB::unprepared('DROP FUNCTION IF EXISTS fn_predicate');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_predicate(p_score DECIMAL(6,2))
RETURNS CHAR(1)
DETERMINISTIC
BEGIN
    IF p_score IS NULL THEN
        RETURN NULL;
    END IF;
    IF p_score >= 90 THEN
        RETURN 'A';
    ELSEIF p_score >= 80 THEN
        RETURN 'B';
    ELSEIF p_score >= 70 THEN
        RETURN 'C';
    END IF;
    RETURN 'D';
END
FN);

        // 2. Normalisasi nilai tugas ke skala 0-100
        DB::unprepared('DROP FUNCTION IF EXISTS fn_score_pct');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_score_pct(p_score DECIMAL(6,2), p_max INT)
RETURNS DECIMAL(6,2)
DETERMINISTIC
BEGIN
    IF p_score IS NULL OR p_max IS NULL OR p_max <= 0 THEN
        RETURN NULL;
    END IF;
    RETURN ROUND(p_score / p_max * 100, 2);
END
FN);

        // 3. Nilai objektif satu sesi ujian (skala 0-100), padanan ExamGrader::objectiveScore()
        DB::unprepared('DROP FUNCTION IF EXISTS fn_exam_objective_score');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_exam_objective_score(p_session_id BIGINT UNSIGNED)
RETURNS DECIMAL(6,2)
READS SQL DATA
BEGIN
    DECLARE v_exam   BIGINT UNSIGNED;
    DECLARE v_nis    VARCHAR(10) {$cs};
    DECLARE v_total  DECIMAL(10,2) DEFAULT 0;
    DECLARE v_earned DECIMAL(10,2) DEFAULT 0;

    SELECT exam_id, students_id INTO v_exam, v_nis
    FROM exam_sessions
    WHERE exam_session_id = p_session_id;

    IF v_exam IS NULL THEN
        RETURN 0;
    END IF;

    SELECT COALESCE(SUM(score_weight), 0) INTO v_total
    FROM exam_questions
    WHERE exam_id = v_exam AND question_type <> 'esai';

    IF v_total <= 0 THEN
        RETURN 0;
    END IF;

    SELECT COALESCE(SUM(a.score), 0) INTO v_earned
    FROM exam_answers a
    JOIN exam_questions q ON q.exam_question_id = a.question_id
    WHERE a.exam_id = v_exam
      AND a.students_id = v_nis
      AND q.question_type <> 'esai';

    RETURN ROUND(v_earned / v_total * 100, 2);
END
FN);

        // 4. Jumlah pelanggaran dalam satu sesi
        DB::unprepared('DROP FUNCTION IF EXISTS fn_violation_count');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_violation_count(p_session_id BIGINT UNSIGNED)
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE v_total INT DEFAULT 0;
    SELECT COUNT(*) INTO v_total FROM exam_violations WHERE exam_session_id = p_session_id;
    RETURN v_total;
END
FN);

        // 5. Rata-rata nilai siswa di satu kelas mata pelajaran
        DB::unprepared('DROP FUNCTION IF EXISTS fn_student_average');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_student_average(p_nis VARCHAR(10) {$cs}, p_cst VARCHAR(20) {$cs})
RETURNS DECIMAL(6,2)
READS SQL DATA
BEGIN
    DECLARE v_avg DECIMAL(6,2) DEFAULT NULL;
    SELECT ROUND(AVG(score), 2) INTO v_avg
    FROM grades
    WHERE students_id = p_nis AND cst_id = p_cst AND score IS NOT NULL;
    RETURN v_avg;
END
FN);

        // 6. Apakah ujian sedang dibuka (terbit dan di dalam rentang waktu)
        DB::unprepared('DROP FUNCTION IF EXISTS fn_is_exam_open');
        DB::unprepared(<<<FN
CREATE FUNCTION fn_is_exam_open(p_exam_id BIGINT UNSIGNED)
RETURNS TINYINT(1)
READS SQL DATA
BEGIN
    DECLARE v_open INT DEFAULT 0;
    SELECT COUNT(*) INTO v_open
    FROM exams
    WHERE exam_id = p_exam_id
      AND is_published = 1
      AND NOW() BETWEEN start_time AND end_time;
    RETURN IF(v_open > 0, 1, 0);
END
FN);
    }

    public function down(): void
    {
        foreach (['fn_predicate', 'fn_score_pct', 'fn_exam_objective_score', 'fn_violation_count', 'fn_student_average', 'fn_is_exam_open'] as $fn) {
            DB::unprepared("DROP FUNCTION IF EXISTS {$fn}");
        }
    }
};
