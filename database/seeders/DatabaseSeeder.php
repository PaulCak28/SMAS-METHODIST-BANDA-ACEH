<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TeacherSeeder::class,
            SubjectSeeder::class,
            SchoolClassSeeder::class,
            ClassSubjectTeacherSeeder::class,
            StudentSeeder::class,
            ExamSeeder::class,
            ExamQuestionSeeder::class,
            KimiaSoalSeeder::class,
            AnnouncementSeeder::class,
            AccountTesterSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}