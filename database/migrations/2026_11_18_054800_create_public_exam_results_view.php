<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW v_public_exam_results AS
            SELECT
                es.exam_session_id,
                es.student_id,
                e.exam_id,
                e.title,
                e.exam_type,
                e.pass_score,
                es.status,
                es.finished_at,
                es.left_at,
                es.updated_at,
                c.name AS class_name,
                sub.name AS subject_name,
                LEAST(
                    100,
                    COALESCE(SUM(
                        CASE
                            WHEN q.question_type <> 'esai' THEN COALESCE(ea.score, 0)
                            ELSE 0
                        END
                    ), 0)
                ) AS objective_score,
                COALESCE(SUM(
                    CASE
                        WHEN q.question_type = 'esai'
                            AND ea.graded_at IS NOT NULL
                            AND ea.score IS NOT NULL
                        THEN ea.score
                        ELSE 0
                    END
                ), 0) AS essay_score,
                SUM(
                    CASE
                        WHEN q.question_type = 'esai'
                            AND (ea.graded_at IS NULL OR ea.score IS NULL)
                        THEN 1
                        ELSE 0
                    END
                ) AS pending_essay_count,
                SUM(CASE WHEN q.question_type = 'esai' THEN 1 ELSE 0 END) AS essay_count,
                LEAST(100, COALESCE(SUM(ea.score), 0)) AS final_score
            FROM exam_sessions AS es
            JOIN exams AS e ON e.exam_id = es.exam_id
            JOIN class_subject_teacher AS cst ON cst.cst_id = e.cst_id
            JOIN classes AS c ON c.class_id = cst.class_id
            JOIN subjects AS sub ON sub.code = cst.subject_code
            LEFT JOIN exam_questions AS q ON q.exam_id = e.exam_id
            LEFT JOIN exam_answers AS ea
                ON ea.question_id = q.exam_question_id
                AND ea.exam_id = e.exam_id
                AND ea.students_id = es.student_id
            WHERE es.status IN ('selesai', 'keluar')
            GROUP BY
                es.exam_session_id,
                es.student_id,
                e.exam_id,
                e.title,
                e.exam_type,
                e.pass_score,
                es.status,
                es.finished_at,
                es.left_at,
                es.updated_at,
                c.name,
                sub.name
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS v_public_exam_results');
    }
};
