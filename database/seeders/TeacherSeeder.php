<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public const TEACHERS = [
        ['Budi Santoso', 'budi.guru', 'Matematika & Geografi', 'S2'],
        ['Nasrul Hanafiah', 'nasrul.guru', 'Matematika', 'S1'],
        ['Zakiyul Vuady, S.Pd', 'zakiyul.guru', 'PJOK', 'S1'],
        ['Merry Rouli Siahaan, S.Th., M.Pd.K', 'merry.guru', 'Agama Kristen & Ibadah', 'S2'],
        ['Daniatul Hikmah, S.Pd', 'daniatul.guru', 'PKWU / Kesenian', 'S1'],
        ['Mulyadi Syahputra, M.Pd', 'mulyadi.guru', 'Bahasa Inggris', 'S2'],
        ['Ajeng Prasasti, S.Pd', 'ajeng.guru', 'Sejarah & Sosiologi', 'S1'],
        ['Rahmat Hidayat, S.Pd., Gr.', 'rahmat.guru', 'Kimia', 'S1'],
        ['Nisrina Umar Abbas, S.Pd.', 'nisrina.guru', 'Biologi', 'S1'],
        ['Reza Fachrulrazi, S.Pd., Gr.', 'reza.guru', 'Bahasa Indonesia', 'S1'],
        ['Drs. Muhammad Toha', 'toha.guru', 'Fisika', 'S1'],
        ['Muhammad Akbarul, SE', 'akbarul.guru', 'Ekonomi', 'S1'],
        ['Oslen Rajagukguk', 'oslen.guru', 'Geografi', 'S1'],
        ['Nurul Husna, S.Pd., M.Pd', 'husna.guru', 'Matematika', 'S2'],
        ['Irhamni Mardhatillah, S.Pd', 'irhamni.guru', 'Bahasa Inggris', 'S1'],
        ['Wahyu, S.Pd', 'wahyu.guru', 'PPKN', 'S1'],
        ['Rayyan Wahyu Maulana, S.Pd', 'rayyan.guru', 'Sosiologi', 'S1'],
        ['Reinaldo Lionojaya, B.ED', 'reinaldo.guru', 'Bahasa Mandarin', 'S1'],
        ['George Rendy, S.Kom', 'george.guru', 'Informatika', 'S1'],
        ['Sheilisa, S.Pd., Gr.', 'sheilisa.guru', 'Kepala Sekolah', 'S1'],
        ['Fransisca, S.Pd', 'fransisca.guru', 'Waka Kesiswaan', 'S1'],
    ];

    public function run(): void
    {
        foreach (self::TEACHERS as $i => [$name, $mail, $spec, $edu]) {
            $nip = 1234567899887 + $i;
            User::updateOrCreate(['user_id' => $nip], [
                'name' => $name,
                'email' => $mail.'@methodistbandaaceh.sch.id',
                'password' => 'password123',
                'role' => 'guru',
            ]);
            Teacher::updateOrCreate(['nip' => $nip], [
                'specialization' => $spec,
                'education_level' => $edu ?? 'S1',
                'address' => 'Jl. Pocut Baren No. 3, Banda Aceh',
                'hire_date' => '2018-07-15',
                'status' => 'aktif',
            ]);
        }
    }
}