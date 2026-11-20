<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ClassSubjectTeacher;
use App\Services\RosterService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RosterScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Peta kelas dari Roster ke class_id database
        $classMapping = [
            'X IPAS A' => 101, // X IPA 1
            'X IPAS B' => 102, // X IPA 2
            'XI A'     => 105, // XI IPA 1
            'XI B'     => 106, // XI IPA 2
            'XII A'    => 109, // XII IPA 1
            'XII B'    => 110, // XII IPA 2
        ];

        // Peta kode mapel Roster ke subject_code database
        $subjectMapping = [
            'MATE'   => 'MTK',
            'BIO'    => 'BIO',
            'FIS'    => 'FIS',
            'FISIKA' => 'FIS',
            'KIM'    => 'KIM',
            'B.INDO' => 'BIN',
            'B.ING'  => 'BIG',
            'GEO'    => 'GEO',
            'SEJ'    => 'SEJ',
            'SOS'    => 'SOS',
            'EKO'    => 'EKO',
            'PJOK'   => 'PJOK',
            'PPKN'   => 'PKN',
            'KES'    => 'SBD',
            'PAK'    => 'AGM',
            'TIK'    => 'INF',
        ];

        // Kosongkan tabel schedules sebelum mengisi ulang
        DB::table('schedules')->truncate();

        $roster = RosterService::getRosterData();

        foreach ($roster as $dayKey => $dayData) {
            foreach ($dayData['rows'] as $row) {
                if (!empty($row['is_break']) || !empty($row['is_special'])) {
                    continue;
                }

                // Parse jam waktu (e.g. "08.10 - 08.50" => "08:10:00", "08:50:00")
                $times = explode('-', $row['time']);
                if (count($times) !== 2) {
                    continue;
                }

                $startTime = str_replace('.', ':', trim($times[0])) . ':00';
                $endTime   = str_replace('.', ':', trim($times[1])) . ':00';

                foreach ($row['slots'] as $className => $slot) {
                    if (!$slot) {
                        continue;
                    }

                    $classId = $classMapping[$className] ?? null;
                    $subjectCode = $subjectMapping[$slot['subject']] ?? null;

                    if (!$classId || !$subjectCode) {
                        continue;
                    }

                    // Cari CST
                    $cst = ClassSubjectTeacher::where('class_id', $classId)
                        ->where('subject_code', $subjectCode)
                        ->first();

                    if ($cst) {
                        Schedule::create([
                            'cst_id'     => $cst->cst_id,
                            'day'        => $dayKey,
                            'start_time' => $startTime,
                            'end_time'   => $endTime,
                        ]);
                    }
                }
            }
        }
    }
}
