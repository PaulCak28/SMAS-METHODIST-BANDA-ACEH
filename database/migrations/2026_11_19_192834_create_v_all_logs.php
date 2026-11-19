<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tables = [
        'announcements' => ['pk' => ['announcement_id'], 'cols' => ['announcement_id', 'title', 'slug', 'link', 'category', 'content', 'image', 'target_roles', 'is_public', 'published_at', 'created_by', 'created_at', 'updated_at']],
        'assignments' => ['pk' => ['assignment_id'], 'cols' => ['assignment_id', 'cst_id', 'title', 'description', 'due_date', 'max_score', 'created_at', 'updated_at', 'allowed_formats']],
        'assignment_submissions' => ['pk' => ['assignment_id', 'students_id'], 'cols' => ['assignment_id', 'students_id', 'file_path', 'submitted_at', 'score', 'feedback', 'created_at', 'updated_at']],
        'classes' => ['pk' => ['class_id'], 'cols' => ['class_id', 'name', 'homeroom_teacher_id', 'academic_year', 'created_at', 'updated_at']],
        'class_enrollments' => ['pk' => ['enrollment_id'], 'cols' => ['enrollment_id', 'students_id', 'cst_id', 'academic_year', 'status', 'enrolled_at', 'left_at', 'created_at', 'updated_at']],
        'class_subject_teacher' => ['pk' => ['cst_id'], 'cols' => ['cst_id', 'class_id', 'subject_code', 'teacher_id', 'academic_year', 'semester', 'join_code', 'created_at', 'updated_at']],
        'cst_students' => ['pk' => ['cst_id', 'students_id'], 'cols' => ['cst_id', 'students_id', 'status', 'joined_at', 'left_at', 'created_at', 'updated_at']],
        'exams' => ['pk' => ['exam_id'], 'cols' => ['exam_id', 'cst_id', 'title', 'exam_type', 'start_time', 'end_time', 'duration_minutes', 'pass_score', 'shuffle_questions', 'shuffle_options', 'is_published', 'instructions', 'created_at', 'updated_at']],
        'exam_answers' => ['pk' => ['exam_answer_id'], 'cols' => ['exam_answer_id', 'exam_id', 'students_id', 'question_id', 'answer', 'answer_file', 'is_correct', 'score', 'graded_by', 'graded_at', 'created_at', 'updated_at']],
        'exam_questions' => ['pk' => ['exam_question_id'], 'cols' => ['exam_question_id', 'exam_id', 'question_text', 'question_type', 'options', 'image_path', 'correct_answer', 'score_weight', 'created_at', 'updated_at']],
        'exam_sessions' => ['pk' => ['exam_session_id'], 'cols' => ['exam_session_id', 'exam_id', 'student_id', 'token', 'started_at', 'finished_at', 'left_at', 'status', 'created_at', 'updated_at']],
        'exam_violations' => ['pk' => ['exam_violation_id'], 'cols' => ['exam_violation_id', 'exam_session_id', 'students_id', 'type', 'detail', 'occurred_at']],
        'materials' => ['pk' => ['material_id'], 'cols' => ['material_id', 'cst_id', 'title', 'description', 'file_path', 'uploaded_at', 'created_at', 'updated_at']],
        'notifications' => ['pk' => ['notification_id'], 'cols' => ['notification_id', 'user_id', 'title', 'message', 'is_read', 'created_at', 'updated_at']],
        'schedules' => ['pk' => ['cst_id', 'day', 'start_time'], 'cols' => ['cst_id', 'day', 'start_time', 'end_time', 'created_at', 'updated_at']],
        'students' => ['pk' => ['nis'], 'cols' => ['nis', 'nisn', 'class_id', 'pob', 'dob', 'address', 'district', 'city', 'province', 'parent_name', 'parent_phone', 'entry_year', 'status', 'gender', 'created_at', 'updated_at']],
        'subjects' => ['pk' => ['code'], 'cols' => ['code', 'name', 'created_at', 'updated_at']],
        'teachers' => ['pk' => ['nip'], 'cols' => ['nip', 'specialization', 'education_level', 'address', 'hire_date', 'status', 'created_at', 'updated_at']],
        'users' => ['pk' => ['user_id'], 'cols' => ['user_id', 'name', 'email', 'email_verified_at', 'role', 'phone', 'photo', 'created_at', 'updated_at']],
    ];

    public function up(): void
    {
        $selects = [];

        foreach ($this->tables as $table => $def) {
            $record = "CONCAT_WS('-', " . implode(', ', array_map(fn ($c) => "CAST(`{$c}` AS CHAR)", $def['pk'])) . ')';
            $json   = 'JSON_OBJECT(' . implode(', ', array_map(fn ($c) => "'{$c}', `{$c}`", $def['cols'])) . ')';

            $selects[] = "SELECT '{$table}' AS source_table, log_id, log_action, log_by, log_at, "
                       . "{$record} AS record_id, {$json} AS row_data FROM `{$table}_logs`";
        }

        DB::statement('DROP VIEW IF EXISTS v_all_logs');
        DB::statement('CREATE VIEW v_all_logs AS ' . implode(' UNION ALL ', $selects));
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_all_logs');
    }
};