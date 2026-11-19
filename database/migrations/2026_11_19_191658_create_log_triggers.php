<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    
    private array $tables = [
        'announcements' => ['announcement_id', 'title', 'slug', 'link', 'category', 'content', 'image', 'target_roles', 'is_public', 'published_at', 'created_by', 'created_at', 'updated_at'],
        'assignments' => ['assignment_id', 'cst_id', 'title', 'description', 'due_date', 'max_score', 'created_at', 'updated_at', 'allowed_formats'],
        'assignment_submissions' => ['assignment_id', 'students_id', 'file_path', 'submitted_at', 'score', 'feedback', 'created_at', 'updated_at'],
        'classes' => ['class_id', 'name', 'homeroom_teacher_id', 'academic_year', 'created_at', 'updated_at'],
        'class_enrollments' => ['enrollment_id', 'students_id', 'cst_id', 'academic_year', 'status', 'enrolled_at', 'left_at', 'created_at', 'updated_at'],
        'class_subject_teacher' => ['cst_id', 'class_id', 'subject_code', 'teacher_id', 'academic_year', 'semester', 'join_code', 'created_at', 'updated_at'],
        'cst_students' => ['cst_id', 'students_id', 'status', 'joined_at', 'left_at', 'created_at', 'updated_at'],
        'exams' => ['exam_id', 'cst_id', 'title', 'exam_type', 'start_time', 'end_time', 'duration_minutes', 'pass_score', 'shuffle_questions', 'shuffle_options', 'is_published', 'instructions', 'created_at', 'updated_at'],
        'exam_answers' => ['exam_answer_id', 'exam_id', 'students_id', 'question_id', 'answer', 'answer_file', 'is_correct', 'score', 'graded_by', 'graded_at', 'created_at', 'updated_at'],
        'exam_questions' => ['exam_question_id', 'exam_id', 'question_text', 'question_type', 'options', 'image_path', 'correct_answer', 'score_weight', 'created_at', 'updated_at'],
        'exam_sessions' => ['exam_session_id', 'exam_id', 'student_id', 'token', 'started_at', 'finished_at', 'left_at', 'status', 'created_at', 'updated_at'],
        'exam_violations' => ['exam_violation_id', 'exam_session_id', 'students_id', 'type', 'detail', 'occurred_at'],
        'materials' => ['material_id', 'cst_id', 'title', 'description', 'file_path', 'uploaded_at', 'created_at', 'updated_at'],
        'notifications' => ['notification_id', 'user_id', 'title', 'message', 'is_read', 'created_at', 'updated_at'],
        'schedules' => ['cst_id', 'day', 'start_time', 'end_time', 'created_at', 'updated_at'],
        'students' => ['nis', 'nisn', 'class_id', 'pob', 'dob', 'address', 'district', 'city', 'province', 'parent_name', 'parent_phone', 'entry_year', 'status', 'gender', 'created_at', 'updated_at'],
        'subjects' => ['code', 'name', 'created_at', 'updated_at'],
        'teachers' => ['nip', 'specialization', 'education_level', 'address', 'hire_date', 'status', 'created_at', 'updated_at'],
        'users' => ['user_id', 'name', 'email', 'email_verified_at', 'role', 'phone', 'photo', 'created_at', 'updated_at'],
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $columns) {
            $log   = "{$table}_logs";
            $cols  = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));
            $new   = implode(', ', array_map(fn ($c) => "NEW.`{$c}`", $columns));
            $old   = implode(', ', array_map(fn ($c) => "OLD.`{$c}`", $columns));
            $same  = implode(' AND ', array_map(fn ($c) => "OLD.`{$c}` <=> NEW.`{$c}`", $columns));
            $by    = 'COALESCE(@app_user_id, USER())';

            $this->dropTriggers($table);

            DB::unprepared("
                CREATE TRIGGER trg_{$table}_ai AFTER INSERT ON `{$table}` FOR EACH ROW
                BEGIN
                    INSERT INTO `{$log}` (log_action, log_by, {$cols})
                    VALUES ('INSERT', {$by}, {$new});
                END
            ");

            DB::unprepared("
                CREATE TRIGGER trg_{$table}_au AFTER UPDATE ON `{$table}` FOR EACH ROW
                BEGIN
                    IF NOT ({$same}) THEN
                        INSERT INTO `{$log}` (log_action, log_by, {$cols})
                        VALUES ('UPDATE_BEFORE', {$by}, {$old});
                        INSERT INTO `{$log}` (log_action, log_by, {$cols})
                        VALUES ('UPDATE_AFTER', {$by}, {$new});
                    END IF;
                END
            ");

            DB::unprepared("
                CREATE TRIGGER trg_{$table}_ad AFTER DELETE ON `{$table}` FOR EACH ROW
                BEGIN
                    INSERT INTO `{$log}` (log_action, log_by, {$cols})
                    VALUES ('DELETE', {$by}, {$old});
                END
            ");
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->tables) as $table) {
            $this->dropTriggers($table);
        }
    }

    private function dropTriggers(string $table): void
    {
        foreach (['ai', 'au', 'ad'] as $suffix) {
            DB::unprepared("DROP TRIGGER IF EXISTS trg_{$table}_{$suffix}");
        }
    }
};