<?php

namespace Database\Seeders;

use App\Models\ClassSubjectTeacher;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassSubjectTeacherSeeder extends Seeder
{
    /**
     * Map mata pelajaran ke NIP guru resmi sesuai SK Pembagian Tugas Guru.
     */
    public const SUBJECT_TEACHER_MAP = [
        'KIM' => '9438-7666-6713-0073', // Rahmat Hidayat, S.Pd., M.Pd., Gr.
        'MTK' => 1234567899899, // Nurul Husna, S.Pd., M.Pd
        'PJOK' => 1234567899889, // Zakiyul Vuady, S.Pd
        'AGM' => 1234567899890, // Merry Rouli Siahaan, S.Th., M.Pd.K
        'SBD' => 1234567899891, // Daniatul Hikmah, S.Pd
        'BIG' => 1234567899900, // Irhamni Mardhatillah, S.Pd (Kelas X) / Mulyadi (Kelas XI-XII)
        'SEJ' => 1234567899893, // Ajeng Prasasti, S.Pd
        'BIO' => 1234567899894, // Nisrina Umar Abbas, S.Pd.
        'BIN' => 1234567899895, // Reza Fachrulrazi, S.Pd., Gr.
        'FIS' => 1234567899896, // Drs. Muhammad Toha
        'EKO' => 1234567899897, // Muhammad Akbarul, SE
        'GEO' => 1234567899898, // Oslen Rajagukguk
        'PKN' => 1234567899901, // Wahyu, S.Pd
        'SOS' => 1234567899893, // Ajeng Prasasti, S.Pd
        'INF' => 1234567899904, // George Rendy, S.Kom
    ];

    public function run(): void
    {
        // Pastikan kelas 101, 102 memiliki pemetaan mapel ke guru yang sesuai
        foreach ([101, 102] as $classId) {
            foreach (array_keys(SubjectSeeder::SUBJECTS) as $code) {
                $teacherNip = self::SUBJECT_TEACHER_MAP[$code] ?? 1234567899899;

                ClassSubjectTeacher::updateOrCreate(['cst_id' => "$classId-$code-G"], [
                    'class_id' => $classId,
                    'subject_code' => $code,
                    'teacher_id' => $teacherNip,
                    'academic_year' => '2025/2026',
                    'semester' => 'ganjil',
                    'join_code' => $code.$classId,
                ]);
            }
        }

        // Khusus Pak Rahmat Hidayat (Kimia): pastikan terdaftar di seluruh 6 kelas Kimia (X IPAS A, X IPAS B, XI A, XI B, XII A, XII B)
        $kimiaClasses = [101, 102, 105, 106, 109, 110];
        foreach ($kimiaClasses as $classId) {
            ClassSubjectTeacher::updateOrCreate(['cst_id' => "$classId-KIM-G"], [
                'class_id' => $classId,
                'subject_code' => 'KIM',
                'teacher_id' => '9438-7666-6713-0073',
                'academic_year' => '2025/2026',
                'semester' => 'ganjil',
                'join_code' => "KIM$classId",
            ]);
        }
    }
}
