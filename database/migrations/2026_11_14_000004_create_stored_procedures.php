<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PROCEDURES = [
        'sp_join_class', 'sp_grade_assignment', 'sp_record_exam_grade', 'sp_sync_student_grades',
        'sp_find_expired_sessions', 'sp_promote_class', 'sp_generate_join_code',
        'sp_exam_participants', 'sp_dashboard_stats', 'sp_public_find_student', 'sp_public_check_grades',
    ];

    private function cs(): string
    {
        $conn = config('database.connections.' . DB::getDefaultConnection());

        return 'CHARACTER SET ' . ($conn['charset'] ?? 'utf8mb4') . ' COLLATE ' . ($conn['collation'] ?? 'utf8mb4_unicode_ci');
    }

    public function up(): void
    {
        $cs = $this->cs();

        foreach (self::PROCEDURES as $p) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$p}");
        }

        // 1. Siswa bergabung ke kelas mata pelajaran memakai kode gabung
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_join_class(IN p_nis VARCHAR(10) {$cs}, IN p_code VARCHAR(8) {$cs})
SQL SECURITY DEFINER
BEGIN
    DECLARE v_cst     VARCHAR(20) {$cs};
    DECLARE v_status  VARCHAR(15) {$cs};
    DECLARE v_joined  INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    SELECT cst_id INTO v_cst FROM class_subject_teacher WHERE join_code = p_code LIMIT 1;
    IF v_cst IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kode gabung tidak valid';
    END IF;

    SELECT status INTO v_status FROM students WHERE nis = p_nis;
    IF v_status IS NULL OR v_status <> 'aktif' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa tidak ditemukan atau tidak aktif';
    END IF;

    SELECT COUNT(*) INTO v_joined FROM cst_students
    WHERE cst_id = v_cst AND students_id = p_nis AND status = 'aktif';
    IF v_joined > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Anda sudah bergabung ke kelas ini';
    END IF;

    INSERT INTO cst_students (cst_id, students_id, status, joined_at, left_at, created_at, updated_at)
    VALUES (v_cst, p_nis, 'aktif', NOW(), NULL, NOW(), NOW())
    ON DUPLICATE KEY UPDATE status = 'aktif', joined_at = NOW(), left_at = NULL, updated_at = NOW();

    COMMIT;

    SELECT v_cst AS cst_id;
END
PROC);

        // 2. Guru menilai tugas: perbarui pengumpulan dan buku nilai dalam satu transaksi
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_grade_assignment(
    IN p_assignment_id BIGINT UNSIGNED,
    IN p_nis           VARCHAR(10) {$cs},
    IN p_nip           VARCHAR(18) {$cs},
    IN p_score         DECIMAL(6,2),
    IN p_feedback      TEXT
)
SQL SECURITY DEFINER
BEGIN
    DECLARE v_cst   VARCHAR(20) {$cs};
    DECLARE v_owner VARCHAR(18) {$cs};
    DECLARE v_max   INT;
    DECLARE v_sub   INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    SELECT a.cst_id, c.teacher_id INTO v_cst, v_max, v_owner
    FROM assignments a
    JOIN class_subject_teacher c ON c.cst_id = a.cst_id
    WHERE a.assignment_id = p_assignment_id;

    IF v_cst IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tugas tidak ditemukan';
    END IF;
    IF v_owner <> p_nip THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Anda bukan guru pengampu kelas ini';
    END IF;
    IF p_score < 0 OR p_score > v_max THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nilai di luar rentang nilai maksimum tugas';
    END IF;

    SELECT COUNT(*) INTO v_sub FROM assignment_submissions
    WHERE assignment_id = p_assignment_id AND students_id = p_nis AND submitted_at IS NOT NULL;
    IF v_sub = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa belum mengumpulkan tugas ini';
    END IF;

    UPDATE assignment_submissions
    SET score = ROUND(p_score), feedback = p_feedback, updated_at = NOW()
    WHERE assignment_id = p_assignment_id AND students_id = p_nis;

    INSERT INTO grades (students_id, cst_id, grade_type, assignment_id, score, graded_by, graded_at, created_at, updated_at)
    VALUES (p_nis, v_cst, 'tugas', p_assignment_id, fn_score_pct(p_score, v_max), p_nip, NOW(), NOW(), NOW())
    ON DUPLICATE KEY UPDATE score = VALUES(score), graded_by = VALUES(graded_by), graded_at = NOW(), updated_at = NOW();

    COMMIT;
END
PROC);

        // 3. Memasukkan nilai satu sesi ujian ke buku nilai (graded_by NULL = dihitung sistem)
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_record_exam_grade(IN p_session_id BIGINT UNSIGNED)
SQL SECURITY DEFINER
BEGIN
    INSERT INTO grades (students_id, cst_id, grade_type, exam_id, score, graded_by, graded_at, created_at, updated_at)
    SELECT s.students_id, e.cst_id, e.exam_type, e.exam_id,
           fn_exam_objective_score(s.exam_session_id), NULL, NOW(), NOW(), NOW()
    FROM exam_sessions s
    JOIN exams e ON e.exam_id = s.exam_id
    WHERE s.exam_session_id = p_session_id
    ON DUPLICATE KEY UPDATE score = VALUES(score), graded_at = NOW(), updated_at = NOW();
END
PROC);

        // 4. Menyinkronkan nilai tugas dan ujian seorang siswa di satu kelas mapel
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_sync_student_grades(IN p_nis VARCHAR(10) {$cs}, IN p_cst VARCHAR(20) {$cs})
SQL SECURITY DEFINER
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    INSERT INTO grades (students_id, cst_id, grade_type, assignment_id, score, graded_by, graded_at, created_at, updated_at)
    SELECT sub.students_id, a.cst_id, 'tugas', a.assignment_id,
           fn_score_pct(sub.score), NULL, NOW(), NOW(), NOW()
    FROM assignment_submissions sub
    JOIN assignments a ON a.assignment_id = sub.assignment_id
    WHERE a.cst_id = p_cst AND sub.students_id = p_nis AND sub.score IS NOT NULL
    ON DUPLICATE KEY UPDATE score = VALUES(score), graded_at = NOW(), updated_at = NOW();

    INSERT INTO grades (students_id, cst_id, grade_type, exam_id, score, graded_by, graded_at, created_at, updated_at)
    SELECT es.students_id, e.cst_id, e.exam_type, e.exam_id,
           fn_exam_objective_score(es.exam_session_id), NULL, NOW(), NOW(), NOW()
    FROM exam_sessions es
    JOIN exams e ON e.exam_id = es.exam_id
    WHERE e.cst_id = p_cst AND es.students_id = p_nis AND es.status = 'selesai'
    ON DUPLICATE KEY UPDATE score = VALUES(score), graded_at = NOW(), updated_at = NOW();

    COMMIT;
END
PROC);

        // 5. Daftar sesi berlangsung yang batas waktunya sudah lewat (dikoreksi oleh Laravel scheduler)
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_find_expired_sessions()
SQL SECURITY DEFINER
BEGIN
    SELECT s.exam_session_id, s.exam_id, s.students_id,
           LEAST(DATE_ADD(s.started_at, INTERVAL e.duration_minutes MINUTE), e.end_time) AS deadline_at
    FROM exam_sessions s
    JOIN exams e ON e.exam_id = s.exam_id
    WHERE s.status = 'berlangsung'
      AND s.started_at IS NOT NULL
      AND NOW() >= LEAST(DATE_ADD(s.started_at, INTERVAL e.duration_minutes MINUTE), e.end_time);
END
PROC);

        // 6. Naik kelas massal
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_promote_class(IN p_from BIGINT UNSIGNED, IN p_to BIGINT UNSIGNED)
SQL SECURITY DEFINER
BEGIN
    DECLARE v_count INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    IF p_from = p_to THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kelas asal dan tujuan tidak boleh sama';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM classes WHERE class_id = p_from)
       OR NOT EXISTS (SELECT 1 FROM classes WHERE class_id = p_to) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kelas asal atau tujuan tidak ditemukan';
    END IF;

    START TRANSACTION;

    UPDATE students SET class_id = p_to, updated_at = NOW()
    WHERE class_id = p_from AND status = 'aktif';
    SET v_count = ROW_COUNT();

    COMMIT;

    SELECT v_count AS promoted_students;
END
PROC);

        // 7. Membuat kode gabung 6 karakter yang unik
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_generate_join_code(IN p_cst_id VARCHAR(20) {$cs})
SQL SECURITY DEFINER
BEGIN
    DECLARE v_code CHAR(8) {$cs};
    DECLARE v_try  INT DEFAULT 0;
    DECLARE v_used INT DEFAULT 1;

    IF NOT EXISTS (SELECT 1 FROM class_subject_teacher WHERE cst_id = p_cst_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kelas mata pelajaran tidak ditemukan';
    END IF;

    REPEAT
        SET v_code = UPPER(SUBSTRING(MD5(CONCAT(RAND(), UUID())), 1, 6));
        SET v_try = v_try + 1;
        SELECT COUNT(*) INTO v_used FROM class_subject_teacher WHERE join_code = v_code;
    UNTIL v_used = 0 OR v_try >= 20
    END REPEAT;

    IF v_used > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal membuat kode gabung yang unik';
    END IF;

    UPDATE class_subject_teacher SET join_code = v_code, updated_at = NOW() WHERE cst_id = p_cst_id;

    SELECT v_code AS join_code;
END
PROC);

        // 8. Peserta satu ujian (termasuk siswa yang belum mulai) untuk halaman monitoring
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_exam_participants(IN p_exam_id BIGINT UNSIGNED)
SQL SECURITY DEFINER
BEGIN
    SELECT s.nis,
           u.name                                   AS student_name,
           es.exam_session_id,
           COALESCE(es.status, 'belum_mulai')       AS session_status,
           es.started_at,
           es.finished_at,
           es.left_at,
           fn_violation_count(es.exam_session_id)   AS violation_count,
           g.score,
           CASE WHEN g.score IS NULL THEN NULL
                WHEN g.score >= e.pass_score THEN 1
                ELSE 0 END                          AS passed
    FROM exams e
    JOIN cst_students cs        ON cs.cst_id = e.cst_id AND cs.status = 'aktif'
    JOIN students s             ON s.nis = cs.students_id
    JOIN users u                ON u.user_id = s.nis
    LEFT JOIN exam_sessions es  ON es.exam_id = e.exam_id AND es.students_id = s.nis
    LEFT JOIN grades g          ON g.exam_id = e.exam_id AND g.students_id = s.nis
    WHERE e.exam_id = p_exam_id
    ORDER BY s.nis;
END
PROC);

        // 9. Statistik dashboard admin dalam satu panggilan
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_dashboard_stats()
SQL SECURITY DEFINER
BEGIN
    SELECT
        (SELECT COUNT(*) FROM students)                                                         AS total_students,
        (SELECT COUNT(*) FROM teachers)                                                         AS total_teachers,
        (SELECT COUNT(*) FROM classes)                                                          AS total_classes,
        (SELECT COUNT(*) FROM subjects)                                                         AS total_subjects,
        (SELECT COUNT(*) FROM exams)                                                            AS total_exams,
        (SELECT COUNT(DISTINCT students_id) FROM exam_sessions)                                 AS exam_participants,
        (SELECT COUNT(*) FROM exam_sessions WHERE status = 'berlangsung')                       AS sessions_running,
        (SELECT COUNT(*) FROM exam_answers WHERE answer_file IS NOT NULL AND graded_at IS NULL) AS pending_essays,
        (SELECT COUNT(*) FROM students WHERE class_id IS NULL)                                  AS students_no_class;
END
PROC);

        // 10. Halaman publik Cek Nilai: nilai hanya keluar bila NISN DAN tanggal lahir cocok
        DB::unprepared(<<<PROC
CREATE PROCEDURE sp_public_check_grades(IN p_nis VARCHAR(10) {$cs}, IN p_dob DATE)
SQL SECURITY DEFINER
BEGIN
    DECLARE v_found INT DEFAULT 0;

    -- NISN dan tanggal lahir harus cocok sekaligus
    SELECT COUNT(*) INTO v_found
    FROM students
    WHERE nis = TRIM(p_nis) AND dob = p_dob AND status = 'aktif';

    -- Pesan sama untuk semua kegagalan agar tidak membocorkan data
    IF v_found = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'NISN atau tanggal lahir tidak cocok';
    END IF;

    SELECT nis, student_name, class_name, cst_id, subject_name, semester, academic_year,
           avg_tugas, avg_kuis, nilai_uts, nilai_uas, rata_rata, predikat
    FROM v_student_grade_summary
    WHERE nis = TRIM(p_nis)
    ORDER BY academic_year, semester, subject_name;
END
PROC);
    }

    public function down(): void
    {
        foreach (self::PROCEDURES as $p) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$p}");
        }
    }
};