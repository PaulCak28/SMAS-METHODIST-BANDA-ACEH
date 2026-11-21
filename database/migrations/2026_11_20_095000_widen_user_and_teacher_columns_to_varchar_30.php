<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function collate(): string
    {
        $conn = config('database.connections.' . DB::getDefaultConnection());
        $charset = $conn['charset'] ?? 'utf8mb4';
        $collation = $conn['collation'] ?? 'utf8mb4_unicode_ci';

        return "CHARACTER SET {$charset} COLLATE {$collation}";
    }

    public function up(): void
    {
        $c = $this->collate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Main tables
        DB::statement("ALTER TABLE users MODIFY user_id VARCHAR(30) {$c} NOT NULL");
        DB::statement("ALTER TABLE teachers MODIFY nip VARCHAR(30) {$c} NOT NULL");
        DB::statement("ALTER TABLE classes MODIFY homeroom_teacher_id VARCHAR(30) {$c} NULL");
        DB::statement("ALTER TABLE class_subject_teacher MODIFY teacher_id VARCHAR(30) {$c} NOT NULL");
        DB::statement("ALTER TABLE grades MODIFY graded_by VARCHAR(30) {$c} NULL");
        DB::statement("ALTER TABLE exam_answers MODIFY graded_by VARCHAR(30) {$c} NULL");
        DB::statement("ALTER TABLE announcements MODIFY created_by VARCHAR(30) {$c} NOT NULL");
        DB::statement("ALTER TABLE notifications MODIFY user_id VARCHAR(30) {$c} NOT NULL");
        DB::statement("ALTER TABLE sessions MODIFY user_id VARCHAR(30) {$c} NULL");

        // Log tables if exist
        $logColumns = [
            ['announcements_logs', 'created_by'],
            ['classes_logs', 'homeroom_teacher_id'],
            ['class_subject_teacher_logs', 'teacher_id'],
            ['grades_logs', 'graded_by'],
            ['notifications_logs', 'user_id'],
            ['teachers_logs', 'nip'],
            ['users_logs', 'user_id'],
        ];

        foreach ($logColumns as [$table, $column]) {
            try {
                DB::statement("ALTER TABLE {$table} MODIFY {$column} VARCHAR(30) {$c} NULL");
            } catch (\Throwable $e) {
                // Skip if log table doesn't exist
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Update stored procedures to accept VARCHAR(30)
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_teacher_overview");
        DB::unprepared("
            CREATE PROCEDURE `sp_teacher_overview`(IN p_teacher_id VARCHAR(30))
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
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS sp_teacher_student_grades");
        DB::unprepared("
            CREATE PROCEDURE `sp_teacher_student_grades`(
                IN p_teacher_id VARCHAR(30),
                IN p_cst_id VARCHAR(20)
            )
            BEGIN
                DECLARE v_owner VARCHAR(30);

                SELECT teacher_id INTO v_owner
                FROM class_subject_teacher
                WHERE cst_id = p_cst_id;

                IF v_owner IS NULL OR v_owner <> p_teacher_id THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Akses ditolak: kelas bukan milik guru ini';
                END IF;

                SELECT
                    s.nis,
                    u.name AS student_name,
                    sc.name AS class_name,
                    ROUND(AVG(CASE WHEN g.grade_type = 'tugas' THEN g.score END), 2) AS avg_assignment,
                    ROUND(AVG(CASE WHEN g.grade_type = 'ujian' THEN g.score END), 2) AS avg_exam,
                    ROUND(AVG(g.score), 2) AS final_score
                FROM cst_students cs
                JOIN students s ON s.nis = cs.students_id
                JOIN users u ON u.user_id = s.nis
                JOIN class_subject_teacher cst ON cst.cst_id = cs.cst_id
                JOIN classes sc ON sc.class_id = cst.class_id
                LEFT JOIN grades g ON g.students_id = s.nis AND g.cst_id = cs.cst_id
                WHERE cs.cst_id = p_cst_id
                  AND cs.status = 'aktif'
                GROUP BY s.nis, u.name, sc.name
                ORDER BY u.name ASC;
            END
        ");

        // Grant execute permissions to methodist_guru
        try {
            $users = DB::select("SELECT User, Host FROM mysql.user WHERE User = 'methodist_guru'");
            foreach ($users as $u) {
                $target = "'{$u->User}'@'{$u->Host}'";
                DB::unprepared("GRANT EXECUTE ON PROCEDURE methodist_aceh.sp_teacher_overview TO {$target};");
                DB::unprepared("GRANT EXECUTE ON PROCEDURE methodist_aceh.sp_teacher_student_grades TO {$target};");
            }
            DB::unprepared("FLUSH PRIVILEGES;");
        } catch (\Throwable $e) {
            // Ignore if mysql.user is not accessible
        }
    }

    public function down(): void
    {
        $c = $this->collate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("ALTER TABLE users MODIFY user_id VARCHAR(18) {$c} NOT NULL");
        DB::statement("ALTER TABLE teachers MODIFY nip VARCHAR(18) {$c} NOT NULL");
        DB::statement("ALTER TABLE classes MODIFY homeroom_teacher_id VARCHAR(18) {$c} NULL");
        DB::statement("ALTER TABLE class_subject_teacher MODIFY teacher_id VARCHAR(18) {$c} NOT NULL");
        DB::statement("ALTER TABLE grades MODIFY graded_by VARCHAR(18) {$c} NULL");
        DB::statement("ALTER TABLE exam_answers MODIFY graded_by VARCHAR(18) {$c} NULL");
        DB::statement("ALTER TABLE announcements MODIFY created_by VARCHAR(18) {$c} NOT NULL");
        DB::statement("ALTER TABLE notifications MODIFY user_id VARCHAR(18) {$c} NOT NULL");
        DB::statement("ALTER TABLE sessions MODIFY user_id VARCHAR(18) {$c} NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
