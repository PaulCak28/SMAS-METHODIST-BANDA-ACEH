<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TRIGGERS = [
        'trg_grades_bi', 'trg_grades_bu', 'trg_grades_ai', 'trg_grades_au', 'trg_grades_ad',
        'trg_submissions_bu', 'trg_exam_sessions_bi', 'trg_exam_sessions_bu',
        'trg_exam_answers_bi', 'trg_exam_answers_bu', 'trg_exam_violations_bi',
        'trg_students_au', 'trg_announcements_ai',
    ];

    private function cs(): string
    {
        $conn = config('database.connections.' . DB::getDefaultConnection());

        return 'CHARACTER SET ' . ($conn['charset'] ?? 'utf8mb4') . ' COLLATE ' . ($conn['collation'] ?? 'utf8mb4_unicode_ci');
    }

    public function up(): void
    {
        $cs = $this->cs();

        foreach (self::TRIGGERS as $t) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$t}");
        }

        // ---------- GRADES: validasi ----------
        DB::unprepared(
"CREATE TRIGGER trg_grades_bi BEFORE INSERT ON grades FOR EACH ROW
BEGIN
    IF NEW.score IS NOT NULL AND (NEW.score < 0 OR NEW.score > 100) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nilai harus berada di antara 0 dan 100';
    END IF;
    IF NEW.assignment_id IS NOT NULL AND NEW.exam_id IS NOT NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Satu nilai hanya boleh berasal dari tugas atau ujian';
    END IF;
END"
);

        DB::unprepared(
"CREATE TRIGGER trg_grades_bu BEFORE UPDATE ON grades FOR EACH ROW
BEGIN
    IF NEW.score IS NOT NULL AND (NEW.score < 0 OR NEW.score > 100) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nilai harus berada di antara 0 dan 100';
    END IF;
    IF NEW.assignment_id IS NOT NULL AND NEW.exam_id IS NOT NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Satu nilai hanya boleh berasal dari tugas atau ujian';
    END IF;
END"
);

        // ---------- GRADES: jejak audit ----------
        DB::unprepared(
"CREATE TRIGGER trg_grades_ai AFTER INSERT ON grades FOR EACH ROW
BEGIN
    INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user)
    VALUES (NEW.grade_id, NEW.students_id, NEW.cst_id, NEW.grade_type, NULL, NEW.score, 'insert', CURRENT_USER());
END"
);

        DB::unprepared(
"CREATE TRIGGER trg_grades_au AFTER UPDATE ON grades FOR EACH ROW
BEGIN
    IF NOT (OLD.score <=> NEW.score) THEN
        INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user)
        VALUES (NEW.grade_id, NEW.students_id, NEW.cst_id, NEW.grade_type, OLD.score, NEW.score, 'update', CURRENT_USER());
    END IF;
END"
);

        DB::unprepared(
"CREATE TRIGGER trg_grades_ad AFTER DELETE ON grades FOR EACH ROW
BEGIN
    INSERT INTO grade_logs (grade_id, students_id, cst_id, grade_type, old_score, new_score, action, db_user)
    VALUES (OLD.grade_id, OLD.students_id, OLD.cst_id, OLD.grade_type, OLD.score, NULL, 'delete', CURRENT_USER());
END"
);

        // ---------- TUGAS ----------
        DB::unprepared(
"CREATE TRIGGER trg_submissions_bu BEFORE UPDATE ON assignment_submissions FOR EACH ROW
BEGIN
    DECLARE v_max INT;
    IF NEW.score IS NOT NULL THEN
        SELECT max_score INTO v_max FROM assignments WHERE assignment_id = NEW.assignment_id;
        IF v_max IS NOT NULL AND NEW.score > v_max THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nilai tugas melebihi nilai maksimum';
        END IF;
    END IF;
END"
);

        // ---------- UJIAN: sesi ----------
        DB::unprepared(
"CREATE TRIGGER trg_exam_sessions_bi BEFORE INSERT ON exam_sessions FOR EACH ROW
BEGIN
    IF fn_is_exam_open(NEW.exam_id) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ujian belum dibuka atau sudah ditutup';
    END IF;
    IF NOT EXISTS (
        SELECT 1 FROM exams e
        JOIN cst_students cs ON cs.cst_id = e.cst_id
        WHERE e.exam_id = NEW.exam_id AND cs.students_id = NEW.students_id AND cs.status = 'aktif'
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa belum bergabung ke kelas mata pelajaran ini';
    END IF;
END"
);

        DB::unprepared(
"CREATE TRIGGER trg_exam_sessions_bu BEFORE UPDATE ON exam_sessions FOR EACH ROW
BEGIN
    IF OLD.status = 'selesai' AND NEW.status <> 'selesai' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sesi ujian yang sudah selesai tidak dapat dibuka kembali';
    END IF;
END"
);

        // ---------- UJIAN: jawaban ----------
        DB::unprepared(
"CREATE TRIGGER trg_exam_answers_bi BEFORE INSERT ON exam_answers FOR EACH ROW
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM exam_sessions WHERE exam_id = NEW.exam_id AND students_id = NEW.students_id
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Siswa belum memulai sesi ujian ini';
    END IF;
END"
);

        // Isi jawaban terkunci setelah sesi berakhir. Kolom score, is_correct, graded_* tetap boleh diubah.
        DB::unprepared(
"CREATE TRIGGER trg_exam_answers_bu BEFORE UPDATE ON exam_answers FOR EACH ROW
BEGIN
    DECLARE v_status VARCHAR(15);
    IF NOT (OLD.answer <=> NEW.answer) OR NOT (OLD.answer_file <=> NEW.answer_file) THEN
        SELECT status INTO v_status FROM exam_sessions
        WHERE exam_id = NEW.exam_id AND students_id = NEW.students_id;
        IF v_status IS NOT NULL AND v_status <> 'berlangsung' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jawaban terkunci karena sesi ujian sudah berakhir';
        END IF;
    END IF;
END"
);

        // ---------- UJIAN: pelanggaran ----------
        DB::unprepared(
"CREATE TRIGGER trg_exam_violations_bi BEFORE INSERT ON exam_violations FOR EACH ROW
BEGIN
    DECLARE v_nis VARCHAR(10) {$cs};
    SELECT students_id INTO v_nis FROM exam_sessions WHERE exam_session_id = NEW.exam_session_id;
    IF v_nis IS NULL OR v_nis <> NEW.students_id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pelanggaran harus dicatat atas nama siswa pemilik sesi';
    END IF;
END"
);

        // ---------- SISWA ----------
        DB::unprepared(
"CREATE TRIGGER trg_students_au AFTER UPDATE ON students FOR EACH ROW
BEGIN
    IF NOT (OLD.status <=> NEW.status) AND NEW.status IN ('lulus', 'pindah', 'drop_out') THEN
        UPDATE cst_students
        SET status = 'keluar', left_at = NOW(), updated_at = NOW()
        WHERE students_id = NEW.nis AND status = 'aktif';
    END IF;
END"
);

        // ---------- PENGUMUMAN -> NOTIFIKASI ----------
        DB::unprepared(
"CREATE TRIGGER trg_announcements_ai AFTER INSERT ON announcements FOR EACH ROW
BEGIN
    INSERT INTO notifications (user_id, title, message, is_read, created_at, updated_at)
    SELECT u.user_id, NEW.title, NEW.content, 0, NOW(), NOW()
    FROM users u
    WHERE JSON_CONTAINS(NEW.target_roles, JSON_QUOTE(u.role));
END"
);
    }

    public function down(): void
    {
        foreach (self::TRIGGERS as $t) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$t}");
        }
    }
};
