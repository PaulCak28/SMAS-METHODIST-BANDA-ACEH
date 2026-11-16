<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buku nilai lengkap
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_gradebook AS
SELECT
    g.grade_id,
    g.students_id                   AS nis,
    su.name                         AS student_name,
    k.name                          AS class_name,
    c.cst_id,
    c.subject_code,
    sb.name                         AS subject_name,
    c.semester,
    c.academic_year,
    g.grade_type,
    COALESCE(a.title, e.title)      AS source_title,
    g.score,
    CASE
        WHEN g.score >= 90 THEN 'A'
        WHEN g.score >= 80 THEN 'B'
        WHEN g.score >= 70 THEN 'C'
        WHEN g.score >= 60 THEN 'D'
        ELSE 'E'
    END                             AS predicate,
    gu.name                         AS graded_by_name,
    g.graded_at
FROM grades g
JOIN students s                 ON s.nis = g.students_id
JOIN users su                   ON su.user_id = s.nis
JOIN class_subject_teacher c    ON c.cst_id = g.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
LEFT JOIN assignments a         ON a.assignment_id = g.assignment_id
LEFT JOIN exams e               ON e.exam_id = g.exam_id
LEFT JOIN users gu              ON gu.user_id = g.graded_by
SQL);

        // 2. Ringkasan nilai per siswa per kelas mapel (dipakai halaman Cek Nilai orang tua)
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_student_grade_summary AS
SELECT
    s.nis,
    u.name                                                          AS student_name,
    k.name                                                          AS class_name,
    c.cst_id,
    sb.name                                                         AS subject_name,
    c.semester,
    c.academic_year,
    ROUND(AVG(CASE WHEN g.grade_type = 'tugas' THEN g.score END), 2) AS avg_tugas,
    ROUND(AVG(CASE WHEN g.grade_type = 'kuis'  THEN g.score END), 2) AS avg_kuis,
    MAX(CASE WHEN g.grade_type = 'uts' THEN g.score END)            AS nilai_uts,
    MAX(CASE WHEN g.grade_type = 'uas' THEN g.score END)            AS nilai_uas,
    ROUND(AVG(g.score), 2)                                          AS rata_rata,
    CASE
        WHEN ROUND(AVG(g.score), 2) >= 90 THEN 'A'
        WHEN ROUND(AVG(g.score), 2) >= 80 THEN 'B'
        WHEN ROUND(AVG(g.score), 2) >= 70 THEN 'C'
        WHEN ROUND(AVG(g.score), 2) >= 60 THEN 'D'
        ELSE 'E'
    END                                                             AS predikat
FROM grades g
JOIN students s                 ON s.nis = g.students_id
JOIN users u                    ON u.user_id = s.nis
JOIN class_subject_teacher c    ON c.cst_id = g.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
GROUP BY s.nis, u.name, k.name, c.cst_id, sb.name, c.semester, c.academic_year
SQL);

        // 3. Hasil ujian per sesi
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_exam_results AS
SELECT
    es.exam_session_id,
    e.exam_id,
    e.title                                             AS exam_title,
    e.exam_type,
    k.name                                              AS class_name,
    sb.name                                             AS subject_name,
    s.nis,
    u.name                                              AS student_name,
    es.status,
    es.started_at,
    es.finished_at,
    es.left_at,
    fn_violation_count(es.exam_session_id)              AS violation_count,
    fn_exam_objective_score(es.exam_session_id)         AS objective_score,
    e.pass_score,
    CASE WHEN es.status = 'selesai'
          AND fn_exam_objective_score(es.exam_session_id) >= e.pass_score
         THEN 1 ELSE 0 END                              AS passed
FROM exam_sessions es
JOIN exams e                    ON e.exam_id = es.exam_id
JOIN class_subject_teacher c    ON c.cst_id = e.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
JOIN students s                 ON s.nis = es.students_id
JOIN users u                    ON u.user_id = s.nis
SQL);

        // 4. Statistik per ujian
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_exam_statistics AS
SELECT
    e.exam_id,
    e.title                                                         AS exam_title,
    e.exam_type,
    k.name                                                          AS class_name,
    sb.name                                                         AS subject_name,
    e.pass_score,
    (SELECT COUNT(*) FROM exam_sessions x WHERE x.exam_id = e.exam_id)                             AS total_sessions,
    (SELECT COUNT(*) FROM exam_sessions x WHERE x.exam_id = e.exam_id AND x.status = 'selesai')    AS finished_sessions,
    COUNT(g.grade_id)                                               AS graded_count,
    ROUND(AVG(g.score), 2)                                          AS avg_score,
    MAX(g.score)                                                    AS max_score,
    MIN(g.score)                                                    AS min_score,
    SUM(CASE WHEN g.score >= e.pass_score THEN 1 ELSE 0 END)        AS passed_count,
    ROUND(100 * SUM(CASE WHEN g.score >= e.pass_score THEN 1 ELSE 0 END) / NULLIF(COUNT(g.grade_id), 0), 2) AS pass_rate
FROM exams e
JOIN class_subject_teacher c    ON c.cst_id = e.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
LEFT JOIN grades g              ON g.exam_id = e.exam_id
GROUP BY e.exam_id, e.title, e.exam_type, k.name, sb.name, e.pass_score
SQL);

        // 5. Rekap pelanggaran per sesi dan jenis
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_violation_summary AS
SELECT
    v.exam_session_id,
    es.exam_id,
    e.title                 AS exam_title,
    v.students_id           AS nis,
    u.name                  AS student_name,
    v.type                  AS violation_type,
    COUNT(*)                AS total,
    MAX(v.occurred_at)      AS last_occurred_at
FROM exam_violations v
JOIN exam_sessions es   ON es.exam_session_id = v.exam_session_id
JOIN exams e            ON e.exam_id = es.exam_id
JOIN users u            ON u.user_id = v.students_id
GROUP BY v.exam_session_id, es.exam_id, e.title, v.students_id, u.name, v.type
SQL);

// 6. Status pengumpulan tugas
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_assignment_status AS
SELECT
    a.assignment_id,
    a.title,
    a.due_date,
    a.cst_id,
    k.name                  AS class_name,
    sb.name                 AS subject_name,
    (SELECT COUNT(*) FROM cst_students cs WHERE cs.cst_id = a.cst_id AND cs.status = 'aktif') AS total_students,
    COUNT(sub.students_id)  AS submitted_count,
    SUM(CASE WHEN sub.score IS NOT NULL THEN 1 ELSE 0 END)           AS graded_count,
    SUM(CASE WHEN sub.submitted_at > a.due_date THEN 1 ELSE 0 END)   AS late_count
FROM assignments a
JOIN class_subject_teacher c    ON c.cst_id = a.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
LEFT JOIN assignment_submissions sub
       ON sub.assignment_id = a.assignment_id AND sub.submitted_at IS NOT NULL
GROUP BY a.assignment_id, a.title, a.due_date, a.cst_id, k.name, sb.name
SQL);

        // 7. Esai yang sudah diunggah tetapi belum dinilai guru
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_essay_pending AS
SELECT
    ea.exam_answer_id,
    e.exam_id,
    e.title                 AS exam_title,
    c.cst_id,
    c.teacher_id,
    k.name                  AS class_name,
    sb.name                 AS subject_name,
    ea.students_id          AS nis,
    u.name                  AS student_name,
    q.exam_question_id,
    ea.answer_file,
    ea.updated_at           AS uploaded_at
FROM exam_answers ea
JOIN exam_questions q           ON q.exam_question_id = ea.question_id AND q.question_type = 'esai'
JOIN exams e                    ON e.exam_id = ea.exam_id
JOIN class_subject_teacher c    ON c.cst_id = e.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
JOIN users u                    ON u.user_id = ea.students_id
WHERE ea.answer_file IS NOT NULL
  AND ea.graded_at IS NULL
SQL);

        // 8. Daftar siswa per kelas mata pelajaran
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_class_roster AS
SELECT
    cs.cst_id,
    k.name                  AS class_name,
    sb.name                 AS subject_name,
    c.teacher_id,
    tu.name                 AS teacher_name,
    s.nis,
    su.name                 AS student_name,
    s.gender,
    cs.status,
    cs.joined_at
FROM cst_students cs
JOIN class_subject_teacher c    ON c.cst_id = cs.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
JOIN students s                 ON s.nis = cs.students_id
JOIN users su                   ON su.user_id = s.nis
LEFT JOIN users tu              ON tu.user_id = c.teacher_id
SQL);

        // 9. Jadwal mengajar
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_teaching_schedule AS
SELECT
    sc.day,
    sc.start_time,
    sc.end_time,
    c.cst_id,
    k.name                  AS class_name,
    sb.name                 AS subject_name,
    c.teacher_id,
    tu.name                 AS teacher_name,
    c.semester,
    c.academic_year
FROM schedules sc
JOIN class_subject_teacher c    ON c.cst_id = sc.cst_id
JOIN classes k                  ON k.class_id = c.class_id
JOIN subjects sb                ON sb.code = c.subject_code
LEFT JOIN users tu              ON tu.user_id = c.teacher_id
SQL);
    }

    public function down(): void
    {
        foreach ([
            'v_gradebook', 'v_student_grade_summary', 'v_exam_results', 'v_exam_statistics',
            'v_violation_summary', 'v_assignment_status', 'v_essay_pending', 'v_class_roster', 'v_teaching_schedule',
        ] as $view) {
            DB::unprepared("DROP VIEW IF EXISTS {$view}");
        }
    }
};