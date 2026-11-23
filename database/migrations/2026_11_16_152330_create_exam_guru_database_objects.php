<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengambil nama database aktif secara dinamis (methodist_aceh di lokal, railway di server)
        $dbName = DB::connection()->getDatabaseName();
        
        DB::unprepared("ALTER DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        
        // 1. Hapus semua trigger, procedure, function, dan view yang berkonflik/rusak
        $existingTriggers = DB::select("SHOW TRIGGERS");
        foreach ($existingTriggers as $trigger) {
            DB::unprepared("DROP TRIGGER IF EXISTS `{$trigger->Trigger}`;");
        }

        $procedures = [
            'sp_create_exam_question',
            'sp_dashboard_stats',
            'sp_exam_participants',
            'sp_find_expired_sessions',
            'sp_force_finish_session',
            'sp_grade_assignment',
            'sp_grade_exam_essay',
            'sp_grade_exam_session',
            'sp_public_check_grades',
            'sp_record_exam_grade',
            'sp_sync_student_grades',
            'sp_teacher_overview',
            'sp_teacher_student_grades',
        ];

        foreach ($procedures as $proc) {
            DB::unprepared("DROP PROCEDURE IF EXISTS `{$proc}`;");
        }

        $functions = [
            'fn_exam_final_score',
            'fn_exam_objective_score',
            'fn_grade_letter',
            'fn_predicate',
            'fn_ungraded_essay_count',
            'fn_violation_count',
        ];

        foreach ($functions as $func) {
            DB::unprepared("DROP FUNCTION IF EXISTS `{$func}`;");
        }

        DB::unprepared("DROP VIEW IF EXISTS `v_exam_results`;");
        DB::unprepared("DROP VIEW IF EXISTS `v_student_grade_summary`;");

        // 2. Buat FUNCTIONS
        DB::unprepared("
            CREATE FUNCTION `fn_grade_letter`(p_score DECIMAL(6,2)) RETURNS VARCHAR(2) CHARSET utf8mb4
            DETERMINISTIC
            BEGIN
                IF p_score IS NULL THEN RETURN '-';
                ELSEIF p_score >= 90 THEN RETURN 'A';
                ELSEIF p_score >= 80 THEN RETURN 'B';
                ELSEIF p_score >= 70 THEN RETURN 'C';
                ELSEIF p_score >= 60 THEN RETURN 'D';
                ELSE RETURN 'E';
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE FUNCTION `fn_predicate`(p_score DECIMAL(6,2)) RETURNS VARCHAR(2) CHARSET utf8mb4
            DETERMINISTIC
            BEGIN
                RETURN fn_grade_letter(p_score);
            END;
        ");

        DB::unprepared("
            CREATE FUNCTION `fn_violation_count`(p_session_id BIGINT UNSIGNED) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_cnt INT DEFAULT 0;
                SELECT COUNT(*) INTO v_cnt
                FROM exam_violations
                WHERE exam_session_id = p_session_id;
                RETURN v_cnt;
            END;
        ");

        DB::unprepared("
            CREATE FUNCTION `fn_exam_objective_score`(p_session_id BIGINT UNSIGNED) RETURNS DECIMAL(6,2)
            READS SQL DATA
            BEGIN
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_score DECIMAL(6,2) DEFAULT 0;

                SELECT exam_id, student_id INTO v_exam_id, v_student_id
                FROM exam_sessions WHERE exam_session_id = p_session_id;

                SELECT COALESCE(SUM(ea.score), 0) INTO v_score
                FROM exam_answers ea
                JOIN exam_questions eq ON ea.question_id = eq.exam_question_id
                WHERE eq.exam_id = v_exam_id
                  AND ea.students_id = v_student_id
                  AND eq.question_type <> 'esai';

                RETURN LEAST(100, v_score);
            END;
        ");

        DB::unprepared("
            CREATE FUNCTION `fn_ungraded_essay_count`(p_exam_id BIGINT UNSIGNED) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_cnt INT DEFAULT 0;

                SELECT COUNT(*) INTO v_cnt
                FROM exam_answers ea
                JOIN exam_questions eq ON ea.question_id = eq.exam_question_id
                JOIN exam_sessions es ON es.exam_id = eq.exam_id AND es.student_id = ea.students_id
                WHERE eq.exam_id = p_exam_id
                  AND eq.question_type = 'esai'
                  AND es.status IN ('selesai', 'keluar')
                  AND (ea.graded_at IS NULL OR ea.score IS NULL);

                RETURN v_cnt;
            END;
        ");

        DB::unprepared("
            CREATE FUNCTION `fn_exam_final_score`(p_session_id BIGINT UNSIGNED) RETURNS DECIMAL(6,2)
            READS SQL DATA
            BEGIN
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_total DECIMAL(6,2) DEFAULT 0;

                SELECT exam_id, student_id INTO v_exam_id, v_student_id
                FROM exam_sessions WHERE exam_session_id = p_session_id;

                SELECT COALESCE(SUM(ea.score), 0) INTO v_total
                FROM exam_answers ea
                JOIN exam_questions eq ON ea.question_id = eq.exam_question_id
                WHERE eq.exam_id = v_exam_id
                  AND ea.students_id = v_student_id;

                RETURN LEAST(100, v_total);
            END;
        ");

        // 3. Perbaiki VIEW
        DB::unprepared("
            CREATE VIEW `v_exam_results` AS
            SELECT
                es.exam_session_id AS exam_session_id,
                e.exam_id AS exam_id,
                e.title AS exam_title,
                e.exam_type AS exam_type,
                k.name AS class_name,
                sb.name AS subject_name,
                s.nis AS nis,
                u.name AS student_name,
                es.status AS status,
                es.started_at AS started_at,
                es.finished_at AS finished_at,
                es.left_at AS left_at,
                fn_violation_count(es.exam_session_id) AS violation_count,
                fn_exam_objective_score(es.exam_session_id) AS objective_score,
                fn_exam_final_score(es.exam_session_id) AS final_score,
                e.pass_score AS pass_score,
                CASE
                    WHEN es.status = 'selesai' AND fn_exam_final_score(es.exam_session_id) >= e.pass_score THEN 1
                    ELSE 0
                END AS passed
            FROM exam_sessions es
            JOIN exams e ON e.exam_id = es.exam_id
            JOIN class_subject_teacher c ON c.cst_id = e.cst_id
            JOIN classes k ON k.class_id = c.class_id
            JOIN subjects sb ON sb.code = c.subject_code
            JOIN students s ON s.nis = es.student_id
            JOIN users u ON u.user_id = s.nis;
        ");

        DB::unprepared("
            CREATE VIEW `v_student_grade_summary` AS
            SELECT
                s.nis AS student_id,
                u.name AS student_name,
                c.name AS class_name,
                sub.name AS subject_name,
                cst.semester,
                cst.academic_year,
                g.grade_type,
                COALESCE(a.title, e.title) AS title,
                g.score,
                fn_predicate(g.score) AS predicate,
                g.graded_at
            FROM grades g
            JOIN students s ON g.students_id = s.nis
            JOIN users u ON s.nis = u.user_id
            JOIN class_subject_teacher cst ON g.cst_id = cst.cst_id
            JOIN classes c ON cst.class_id = c.class_id
            JOIN subjects sub ON cst.subject_code = sub.code
            LEFT JOIN assignments a ON a.assignment_id = g.assignment_id
            LEFT JOIN exams e ON e.exam_id = g.exam_id;
        ");

        // 4. Buat STORED PROCEDURES
        DB::unprepared("
            CREATE PROCEDURE `sp_dashboard_stats`()
            READS SQL DATA
            BEGIN
                SELECT
                    (SELECT COUNT(*) FROM users) AS total_users,
                    (SELECT COUNT(*) FROM students WHERE status = 'aktif') AS total_students,
                    (SELECT COUNT(*) FROM teachers WHERE status = 'aktif') AS total_teachers,
                    (SELECT COUNT(*) FROM classes) AS total_classes,
                    (SELECT COUNT(*) FROM subjects) AS total_subjects,
                    (SELECT COUNT(*) FROM exams WHERE is_published = 1) AS published_exams,
                    (SELECT COUNT(*) FROM exam_sessions WHERE status = 'berlangsung') AS active_sessions,
                    (SELECT COUNT(*) FROM announcements) AS total_announcements;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_create_exam_question`(
                IN p_exam_id BIGINT UNSIGNED,
                IN p_teacher_id VARCHAR(18),
                IN p_question_type VARCHAR(30),
                IN p_question_text TEXT,
                IN p_options JSON,
                IN p_correct_answer TEXT,
                IN p_score_weight DECIMAL(6,2)
            )
            MODIFIES SQL DATA
            BEGIN
                DECLARE v_owner VARCHAR(18);
                DECLARE v_new_id BIGINT UNSIGNED;

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                SELECT cst.teacher_id INTO v_owner
                FROM exams e
                JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                WHERE e.exam_id = p_exam_id;

                IF v_owner IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ujian tidak ditemukan.';
                ELSEIF v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Akses ditolak: bukan pemilik ujian ini.';
                END IF;

                START TRANSACTION;
                    INSERT INTO exam_questions (
                        exam_id, question_type, question_text, options, correct_answer, score_weight, created_at, updated_at
                    ) VALUES (
                        p_exam_id, p_question_type, p_question_text, p_options, p_correct_answer, COALESCE(p_score_weight, 1.00), NOW(), NOW()
                    );
                    SET v_new_id = LAST_INSERT_ID();
                COMMIT;

                SELECT * FROM exam_questions WHERE exam_question_id = v_new_id;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_exam_participants`(IN p_exam_id BIGINT UNSIGNED)
            READS SQL DATA
            BEGIN
                DECLARE v_cst VARCHAR(20);
                SELECT cst_id INTO v_cst FROM exams WHERE exam_id = p_exam_id;

                SELECT
                    s.nis AS student_id,
                    u.name AS student_name,
                    es.exam_session_id,
                    COALESCE(es.status, 'belum_mulai') AS status,
                    es.started_at,
                    es.finished_at,
                    CASE WHEN es.exam_session_id IS NOT NULL THEN fn_exam_final_score(es.exam_session_id) ELSE NULL END AS total_score,
                    CASE WHEN es.exam_session_id IS NOT NULL THEN fn_violation_count(es.exam_session_id) ELSE 0 END AS violation_count
                FROM cst_students cs
                JOIN students s ON s.nis = cs.students_id
                JOIN users u ON u.user_id = s.nis
                LEFT JOIN exam_sessions es ON es.exam_id = p_exam_id AND es.student_id = s.nis
                WHERE cs.cst_id = v_cst
                ORDER BY u.name;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_find_expired_sessions`()
            READS SQL DATA
            BEGIN
                SELECT
                    s.exam_session_id,
                    s.exam_id,
                    s.student_id,
                    u.name AS student_name,
                    e.title AS exam_title,
                    s.started_at,
                    e.duration_minutes,
                    TIMESTAMPDIFF(MINUTE, s.started_at, NOW()) AS elapsed_minutes
                FROM exam_sessions s
                JOIN exams e ON s.exam_id = e.exam_id
                JOIN users u ON s.student_id = u.user_id
                WHERE s.status = 'berlangsung'
                  AND TIMESTAMPDIFF(MINUTE, s.started_at, NOW()) >= e.duration_minutes;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_force_finish_session`(
                IN p_session_id BIGINT UNSIGNED,
                IN p_teacher_id VARCHAR(18),
                IN p_reason VARCHAR(255)
            )
            MODIFIES SQL DATA
            BEGIN
                DECLARE v_owner VARCHAR(18);
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_cst_id VARCHAR(20);
                DECLARE v_type VARCHAR(20);
                DECLARE v_final DECIMAL(6,2);

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                SELECT es.exam_id, es.student_id, e.cst_id, e.exam_type, cst.teacher_id
                INTO v_exam_id, v_student_id, v_cst_id, v_type, v_owner
                FROM exam_sessions es
                JOIN exams e ON e.exam_id = es.exam_id
                JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                WHERE es.exam_session_id = p_session_id;

                IF v_owner IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sesi ujian tidak ditemukan.';
                ELSEIF v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Akses ditolak: bukan pengampu kelas ini.';
                END IF;

                START TRANSACTION;
                    UPDATE exam_sessions
                    SET status = 'keluar', left_at = NOW(), finished_at = NOW(), updated_at = NOW()
                    WHERE exam_session_id = p_session_id;

                    INSERT INTO exam_violations (exam_session_id, students_id, type, detail, occurred_at)
                    VALUES (p_session_id, v_student_id, 'window_blur', COALESCE(p_reason, 'Dihentikan paksa oleh guru'), NOW());

                    SET v_final = fn_exam_final_score(p_session_id);

                    INSERT INTO grades (students_id, cst_id, grade_type, exam_id, score, graded_by, graded_at, created_at, updated_at)
                    VALUES (v_student_id, v_cst_id, v_type, v_exam_id, v_final, p_teacher_id, NOW(), NOW(), NOW())
                    ON DUPLICATE KEY UPDATE score = v_final, graded_by = p_teacher_id, graded_at = NOW(), updated_at = NOW();
                COMMIT;

                SELECT p_session_id AS exam_session_id, v_final AS final_score;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_grade_assignment`(
                IN p_assignment_id BIGINT UNSIGNED,
                IN p_student_id VARCHAR(10),
                IN p_teacher_id VARCHAR(18),
                IN p_score DECIMAL(5,2),
                IN p_feedback TEXT
            )
            MODIFIES SQL DATA
            BEGIN
                DECLARE v_owner VARCHAR(18);
                DECLARE v_cst_id VARCHAR(20);

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                SELECT cst.teacher_id, a.cst_id
                INTO v_owner, v_cst_id
                FROM assignments a
                JOIN class_subject_teacher cst ON cst.cst_id = a.cst_id
                WHERE a.assignment_id = p_assignment_id;

                IF v_owner IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tugas tidak ditemukan.';
                ELSEIF v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Akses ditolak: bukan pengampu tugas ini.';
                END IF;

                START TRANSACTION;
                    UPDATE assignment_submissions
                    SET score = p_score, feedback = p_feedback, updated_at = NOW()
                    WHERE assignment_id = p_assignment_id AND students_id = p_student_id;

                    INSERT INTO grades (students_id, cst_id, grade_type, assignment_id, score, graded_by, graded_at, created_at, updated_at)
                    VALUES (p_student_id, v_cst_id, 'tugas', p_assignment_id, p_score, p_teacher_id, NOW(), NOW(), NOW())
                    ON DUPLICATE KEY UPDATE score = p_score, graded_by = p_teacher_id, graded_at = NOW(), updated_at = NOW();
                COMMIT;

                SELECT * FROM assignment_submissions
                WHERE assignment_id = p_assignment_id AND students_id = p_student_id;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_grade_exam_essay`(
                IN p_session_id BIGINT UNSIGNED,
                IN p_question_id BIGINT UNSIGNED,
                IN p_teacher_id VARCHAR(18),
                IN p_score DECIMAL(6,2)
            )
            MODIFIES SQL DATA
            BEGIN
                DECLARE v_owner VARCHAR(18);
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_cst_id VARCHAR(20);
                DECLARE v_type VARCHAR(20);
                DECLARE v_weight DECIMAL(6,2);
                DECLARE v_final DECIMAL(6,2);

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                SELECT es.exam_id, es.student_id, e.cst_id, e.exam_type, cst.teacher_id
                INTO v_exam_id, v_student_id, v_cst_id, v_type, v_owner
                FROM exam_sessions es
                JOIN exams e ON e.exam_id = es.exam_id
                JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                WHERE es.exam_session_id = p_session_id;

                IF v_owner IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sesi ujian tidak ditemukan.';
                ELSEIF v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Akses ditolak: bukan pengampu ujian ini.';
                END IF;

                SELECT score_weight INTO v_weight
                FROM exam_questions
                WHERE exam_question_id = p_question_id AND exam_id = v_exam_id;

                IF v_weight IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Soal tidak ditemukan pada ujian ini.';
                ELSEIF p_score < 0 OR p_score > v_weight THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Skor esai melebihi bobot maksimum soal.';
                END IF;

                START TRANSACTION;
                    UPDATE exam_answers
                    SET score = p_score,
                        is_correct = CASE WHEN p_score > 0 THEN 1 ELSE 0 END,
                        graded_by = p_teacher_id,
                        graded_at = NOW(),
                        updated_at = NOW()
                    WHERE question_id = p_question_id AND students_id = v_student_id;

                    SET v_final = fn_exam_final_score(p_session_id);

                    INSERT INTO grades (students_id, cst_id, grade_type, exam_id, score, graded_by, graded_at, created_at, updated_at)
                    VALUES (v_student_id, v_cst_id, v_type, v_exam_id, v_final, p_teacher_id, NOW(), NOW(), NOW())
                    ON DUPLICATE KEY UPDATE score = v_final, graded_by = p_teacher_id, graded_at = NOW(), updated_at = NOW();
                COMMIT;

                SELECT p_session_id AS exam_session_id, v_final AS final_score;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_record_exam_grade`(IN p_session_id BIGINT UNSIGNED)
            MODIFIES SQL DATA
            BEGIN
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_cst_id VARCHAR(20);
                DECLARE v_exam_type VARCHAR(20);
                DECLARE v_teacher_id VARCHAR(18);
                DECLARE v_final DECIMAL(6,2);

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                SELECT s.student_id, s.exam_id, e.cst_id, e.exam_type, cst.teacher_id
                INTO v_student_id, v_exam_id, v_cst_id, v_exam_type, v_teacher_id
                FROM exam_sessions s
                JOIN exams e ON s.exam_id = e.exam_id
                JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                WHERE s.exam_session_id = p_session_id;

                SET v_final = fn_exam_final_score(p_session_id);

                START TRANSACTION;
                    UPDATE exam_sessions
                    SET status = 'selesai',
                        finished_at = COALESCE(finished_at, NOW()),
                        updated_at = NOW()
                    WHERE exam_session_id = p_session_id;

                    INSERT INTO grades (students_id, cst_id, grade_type, exam_id, score, graded_by, graded_at, created_at, updated_at)
                    VALUES (v_student_id, v_cst_id, v_exam_type, v_exam_id, v_final, v_teacher_id, NOW(), NOW(), NOW())
                    ON DUPLICATE KEY UPDATE score = v_final, graded_at = NOW(), updated_at = NOW();
                COMMIT;

                SELECT v_student_id AS student_id, v_exam_id AS exam_id, v_final AS final_score;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_public_check_grades`(
                IN p_student_id VARCHAR(10),
                IN p_dob DATE
            )
            READS SQL DATA
            BEGIN
                DECLARE v_valid INT DEFAULT 0;

                SELECT COUNT(*) INTO v_valid
                FROM students s
                WHERE s.nis = p_student_id
                  AND s.dob = p_dob
                  AND s.status = 'aktif';

                IF v_valid = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'NIS atau tanggal lahir tidak cocok.';
                END IF;

                SELECT * FROM v_student_grade_summary WHERE student_id = p_student_id;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_teacher_overview`(IN p_teacher_id VARCHAR(18))
            READS SQL DATA
            BEGIN
                SELECT
                    cst.cst_id,
                    c.name AS class_name,
                    sub.name AS subject_name,
                    (SELECT COUNT(*) FROM cst_students cs WHERE cs.cst_id = cst.cst_id AND cs.status = 'aktif') AS student_count,
                    (SELECT COUNT(*) FROM assignments a WHERE a.cst_id = cst.cst_id) AS assignment_count,
                    (SELECT COUNT(*) FROM exams e WHERE e.cst_id = cst.cst_id) AS exam_count,
                    (SELECT COUNT(*) FROM exams e WHERE e.cst_id = cst.cst_id AND e.is_published = 1) AS published_exam_count,
                    (SELECT COALESCE(SUM(fn_ungraded_essay_count(e.exam_id)), 0) FROM exams e WHERE e.cst_id = cst.cst_id) AS ungraded_essays
                FROM class_subject_teacher cst
                JOIN classes c ON c.class_id = cst.class_id
                JOIN subjects sub ON sub.code = cst.subject_code
                WHERE cst.teacher_id = p_teacher_id
                ORDER BY c.name, sub.name;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE `sp_teacher_student_grades`(
                IN p_teacher_id VARCHAR(18),
                IN p_cst_id VARCHAR(20)
            )
            READS SQL DATA
            BEGIN
                DECLARE v_owner VARCHAR(18);
                SELECT teacher_id INTO v_owner FROM class_subject_teacher WHERE cst_id = p_cst_id;

                IF v_owner IS NULL OR v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Akses ditolak: Anda tidak mengampu kelas ini.';
                END IF;

                SELECT
                    s.nis AS student_id,
                    u.name AS student_name,
                    ROUND(AVG(CASE WHEN g.grade_type = 'tugas' THEN g.score END), 2) AS avg_tugas,
                    ROUND(AVG(CASE WHEN g.grade_type = 'kuis' THEN g.score END), 2) AS avg_kuis,
                    MAX(CASE WHEN g.grade_type = 'uts' THEN g.score END) AS nilai_uts,
                    MAX(CASE WHEN g.grade_type = 'uas' THEN g.score END) AS nilai_uas,
                    ROUND(AVG(g.score), 2) AS rata_rata,
                    fn_predicate(ROUND(AVG(g.score), 2)) AS predikat
                FROM cst_students cs
                JOIN students s ON s.nis = cs.students_id
                JOIN users u ON u.user_id = s.nis
                LEFT JOIN grades g ON g.students_id = s.nis AND g.cst_id = p_cst_id
                WHERE cs.cst_id = p_cst_id
                GROUP BY s.nis, u.name
                ORDER BY u.name;
            END;
        ");

        // 5. Buat TRIGGERS
        DB::unprepared("
            CREATE TRIGGER `trg_announcements_ai` AFTER INSERT ON `announcements` FOR EACH ROW
            BEGIN
                INSERT INTO notifications (user_id, title, message, is_read, created_at, updated_at)
                SELECT
                    u.user_id,
                    CONCAT('Pengumuman: ', NEW.title),
                    LEFT(NEW.content, 255),
                    0,
                    NOW(),
                    NOW()
                FROM users u
                WHERE NEW.target_roles IS NULL
                   OR JSON_LENGTH(NEW.target_roles) = 0
                   OR JSON_CONTAINS(NEW.target_roles, JSON_QUOTE(u.role));
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_exam_sessions_bi` BEFORE INSERT ON `exam_sessions` FOR EACH ROW
            BEGIN
                DECLARE v_cst VARCHAR(20);
                DECLARE v_pub TINYINT(1);
                DECLARE v_start DATETIME;
                DECLARE v_end DATETIME;
                DECLARE v_enrolled INT DEFAULT 0;
                DECLARE v_existing INT DEFAULT 0;

                SELECT cst_id, is_published, start_time, end_time
                INTO v_cst, v_pub, v_start, v_end
                FROM exams WHERE exam_id = NEW.exam_id;

                IF v_cst IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ujian tidak ditemukan.';
                ELSEIF v_pub = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ujian belum dipublikasikan.';
                ELSEIF v_start IS NOT NULL AND NOW() < v_start THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ujian belum dimulai.';
                ELSEIF v_end IS NOT NULL AND NOW() > v_end THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Waktu ujian sudah berakhir.';
                END IF;

                SELECT COUNT(*) INTO v_enrolled
                FROM cst_students WHERE cst_id = v_cst AND students_id = NEW.student_id AND status = 'aktif';

                IF v_enrolled = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa tidak terdaftar pada kelas ujian ini.';
                END IF;

                SELECT COUNT(*) INTO v_existing
                FROM exam_sessions WHERE exam_id = NEW.exam_id AND student_id = NEW.student_id;

                IF v_existing > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa sudah memiliki sesi untuk ujian ini.';
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_exam_answers_bi` BEFORE INSERT ON `exam_answers` FOR EACH ROW
            BEGIN
                DECLARE v_type VARCHAR(30);
                DECLARE v_correct TEXT;
                DECLARE v_weight DECIMAL(6,2);

                SELECT question_type, correct_answer, score_weight
                INTO v_type, v_correct, v_weight
                FROM exam_questions
                WHERE exam_question_id = NEW.question_id;

                IF v_type <> 'esai' THEN
                    IF NEW.answer IS NOT NULL AND v_correct IS NOT NULL
                       AND TRIM(BOTH '\"' FROM NEW.answer) = TRIM(BOTH '\"' FROM v_correct) THEN
                        SET NEW.is_correct = 1;
                        SET NEW.score = v_weight;
                    ELSE
                        SET NEW.is_correct = 0;
                        SET NEW.score = 0;
                    END IF;
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_exam_answers_bu` BEFORE UPDATE ON `exam_answers` FOR EACH ROW
            BEGIN
                DECLARE v_type VARCHAR(30);
                DECLARE v_correct TEXT;
                DECLARE v_weight DECIMAL(6,2);

                SELECT question_type, correct_answer, score_weight
                INTO v_type, v_correct, v_weight
                FROM exam_questions
                WHERE exam_question_id = NEW.question_id;

                IF v_type <> 'esai' THEN
                    IF NEW.answer IS NOT NULL AND v_correct IS NOT NULL
                       AND TRIM(BOTH '\"' FROM NEW.answer) = TRIM(BOTH '\"' FROM v_correct) THEN
                        SET NEW.is_correct = 1;
                        SET NEW.score = v_weight;
                    ELSE
                        SET NEW.is_correct = 0;
                        SET NEW.score = 0;
                    END IF;
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_exam_violations_ai` AFTER INSERT ON `exam_violations` FOR EACH ROW
            BEGIN
                DECLARE v_total INT DEFAULT 0;
                DECLARE v_exam_id BIGINT UNSIGNED;
                DECLARE v_student_id VARCHAR(10);
                DECLARE v_teacher_id VARCHAR(18);
                DECLARE v_student_name VARCHAR(100);
                DECLARE v_exam_title VARCHAR(255);

                SELECT COUNT(*) INTO v_total FROM exam_violations WHERE exam_session_id = NEW.exam_session_id;

                SELECT es.exam_id, es.student_id, cst.teacher_id, u.name, e.title
                INTO v_exam_id, v_student_id, v_teacher_id, v_student_name, v_exam_title
                FROM exam_sessions es
                JOIN exams e ON e.exam_id = es.exam_id
                JOIN class_subject_teacher cst ON cst.cst_id = e.cst_id
                JOIN users u ON u.user_id = es.student_id
                WHERE es.exam_session_id = NEW.exam_session_id;

                IF v_total >= 3 THEN
                    UPDATE exam_sessions
                    SET status = 'keluar', left_at = NOW(), finished_at = NOW(), updated_at = NOW()
                    WHERE exam_session_id = NEW.exam_session_id AND status = 'berlangsung';

                    IF v_teacher_id IS NOT NULL THEN
                        INSERT INTO notifications (user_id, title, message, is_read, created_at, updated_at)
                        VALUES (
                            v_teacher_id,
                            'Pelanggaran Ujian (Auto-Stop)',
                            CONCAT(v_student_name, ' (', v_student_id, ') mencapai ', v_total, ' pelanggaran pada ujian \"', v_exam_title, '\" dan otomatis dikeluarkan.'),
                            0,
                            NOW(),
                            NOW()
                        );
                    END IF;
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_grades_ai` AFTER INSERT ON `grades` FOR EACH ROW
            BEGIN
                INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user, changed_at)
                VALUES (NEW.grade_id, NEW.students_id, NEW.cst_id, NEW.grade_type, NULL, NEW.score, 'insert', USER(), NOW());
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_grades_au` AFTER UPDATE ON `grades` FOR EACH ROW
            BEGIN
                INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user, changed_at)
                VALUES (NEW.grade_id, NEW.students_id, NEW.cst_id, NEW.grade_type, OLD.score, NEW.score, 'update', USER(), NOW());
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER `trg_grades_ad` AFTER DELETE ON `grades` FOR EACH ROW
            BEGIN
                INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user, changed_at)
                VALUES (OLD.grade_id, OLD.students_id, OLD.cst_id, OLD.grade_type, OLD.score, NULL, 'delete', USER(), NOW());
            END;
        ");

        // 6. Berikan hak akses tabel & EXECUTE menggunakan nama database yang dinamis ($dbName)
        $users = DB::select("SELECT User, Host FROM mysql.user WHERE User IN ('methodist_admin', 'methodist_guru', 'methodist_siswa')");
        foreach ($users as $u) {
            $target = "'{$u->User}'@'{$u->Host}'";
            if ($u->User === 'methodist_admin') {
                DB::unprepared("GRANT ALL PRIVILEGES ON `{$dbName}`.* TO {$target};");
            } elseif ($u->User === 'methodist_guru') {
                DB::unprepared("GRANT SELECT, INSERT, UPDATE, DELETE ON `{$dbName}`.* TO {$target};");
                $guruProcs = [
                    'sp_create_exam_question',
                    'sp_exam_participants',
                    'sp_find_expired_sessions',
                    'sp_force_finish_session',
                    'sp_grade_assignment',
                    'sp_grade_exam_essay',
                    'sp_teacher_overview',
                    'sp_teacher_student_grades',
                ];
                foreach ($guruProcs as $p) {
                    DB::unprepared("GRANT EXECUTE ON PROCEDURE `{$dbName}`.{$p} TO {$target};");
                }
                $guruFuncs = [
                    'fn_exam_final_score',
                    'fn_exam_objective_score',
                    'fn_grade_letter',
                    'fn_predicate',
                    'fn_ungraded_essay_count',
                    'fn_violation_count',
                ];
                foreach ($guruFuncs as $f) {
                    DB::unprepared("GRANT EXECUTE ON FUNCTION `{$dbName}`.{$f} TO {$target};");
                }
            } elseif ($u->User === 'methodist_siswa') {
                DB::unprepared("GRANT SELECT, INSERT, UPDATE, DELETE ON `{$dbName}`.* TO {$target};");
                DB::unprepared("GRANT EXECUTE ON PROCEDURE `{$dbName}`.sp_record_exam_grade TO {$target};");
                DB::unprepared("GRANT EXECUTE ON PROCEDURE `{$dbName}`.sp_public_check_grades TO {$target};");
                DB::unprepared("GRANT EXECUTE ON FUNCTION `{$dbName}`.fn_exam_final_score TO {$target};");
                DB::unprepared("GRANT EXECUTE ON FUNCTION `{$dbName}`.fn_exam_objective_score TO {$target};");
            }
        }
        DB::unprepared("FLUSH PRIVILEGES;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};