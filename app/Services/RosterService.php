<?php

namespace App\Services;

class RosterService
{
    public const ACADEMIC_YEAR = '2026 - 2027';
    public const SCHOOL_NAME = 'SMAS METHODIST BANDA ACEH';
    public const PRINCIPAL_NAME = 'Mardin Laoli, M.Pd';
    public const CURRICULUM_HEAD = 'Nasrul Hanafiah';
    public const DECREE_DATE = 'Banda Aceh, 12 Juli 2026';

    public const CLASSES = [
        'X IPAS A' => ['label' => 'X IPAS A', 'grade' => 'X', 'major' => 'IPAS', 'color' => '#123B60'],
        'X IPAS B' => ['label' => 'X IPAS B', 'grade' => 'X', 'major' => 'IPAS', 'color' => '#123B60'],
        'XI A'     => ['label' => 'XI A',     'grade' => 'XI', 'major' => 'Umum', 'color' => '#1B4D7E'],
        'XI B'     => ['label' => 'XI B',     'grade' => 'XI', 'major' => 'Umum', 'color' => '#1B4D7E'],
        'XII A'    => ['label' => 'XII A',    'grade' => 'XII', 'major' => 'Umum', 'color' => '#267BB5'],
        'XII B'    => ['label' => 'XII B',    'grade' => 'XII', 'major' => 'Umum', 'color' => '#267BB5'],
    ];

    public const TEACHERS = [
        'DN'   => ['code' => 'DN',   'name' => 'Guru Kesenian (DN)', 'subject' => 'KES', 'desc' => 'Kesenian / Seni Budaya'],
        'RH'   => ['code' => 'RH',   'name' => 'Rina Lestari, S.Pd (RH)', 'subject' => 'KIM', 'desc' => 'Kimia'],
        'RZ'   => ['code' => 'RZ',   'name' => 'Maria Simanjuntak, S.Pd (RZ)', 'subject' => 'B.INDO', 'desc' => 'Bahasa Indonesia'],
        'AK'   => ['code' => 'AK',   'name' => 'Daniel Sitompul, S.E (AK)', 'subject' => 'EKO', 'desc' => 'Ekonomi'],
        'AP'   => ['code' => 'AP',   'name' => 'Cut Nyak Dewi, S.Pd (AP)', 'subject' => 'SEJ / SOS', 'desc' => 'Sejarah & Sosiologi'],
        'RE'   => ['code' => 'RE',   'name' => 'Guru Bahasa Mandarin (RE)', 'subject' => 'MAND', 'desc' => 'Bahasa Mandarin'],
        'MT'   => ['code' => 'MT',   'name' => 'Guru Fisika (MT)', 'subject' => 'FISIKA', 'desc' => 'Fisika'],
        'MS'   => ['code' => 'MS',   'name' => 'John Panjaitan, S.Pd (MS)', 'subject' => 'B.ING', 'desc' => 'Bahasa Inggris'],
        'NN'   => ['code' => 'NN',   'name' => 'Budi Santoso, M.Pd (NN)', 'subject' => 'MATE', 'desc' => 'Matematika'],
        'NR'   => ['code' => 'NR',   'name' => 'Andi Wijaya, S.Pd (NR)', 'subject' => 'BIO', 'desc' => 'Biologi'],
        'ZV'   => ['code' => 'ZV',   'name' => 'John Panjaitan, S.Pd (ZV)', 'subject' => 'PJOK', 'desc' => 'Pendidikan Jasmani'],
        'MR'   => ['code' => 'MR',   'name' => 'Guru PAK (MR)', 'subject' => 'PAK', 'desc' => 'Pendidikan Agama Kristen'],
        'GR'   => ['code' => 'GR',   'name' => 'Guru TIK (GR)', 'subject' => 'TIK', 'desc' => 'Teknologi Informasi & Komunikasi'],
        'OS'   => ['code' => 'OS',   'name' => 'Budi Santoso, M.Pd (OS)', 'subject' => 'GEO', 'desc' => 'Geografi'],
        'IM'   => ['code' => 'IM',   'name' => 'Guru Bahasa Inggris (IM)', 'subject' => 'B.ING', 'desc' => 'Bahasa Inggris'],
        'NH'   => ['code' => 'NH',   'name' => 'Nasrul Hanafiah (NH)', 'subject' => 'MATE', 'desc' => 'Matematika / Waka Kurikulum'],
        'WH'   => ['code' => 'WH',   'name' => 'Siti Rahma, S.Pd (WH)', 'subject' => 'PPKN', 'desc' => 'Pendidikan Pancasila & Kewarganegaraan'],
        'RW'   => ['code' => 'RW',   'name' => 'Guru Sosiologi (RW)', 'subject' => 'SOS', 'desc' => 'Sosiologi'],
        '#REF!'=> ['code' => '#REF!','name' => 'Tim Pengajar Fisika', 'subject' => 'FIS', 'desc' => 'Fisika'],
    ];

    public const SUBJECT_STYLES = [
        'BIO' => [
            'name' => 'Biologi',
            'bg' => '#22C55E',
            'text' => '#FFFFFF',
            'badge' => 'background:#22C55E; color:#FFFFFF;',
            'tag' => 'bg-emerald-50 text-emerald-800 border-emerald-300'
        ],
        'KIM' => [
            'name' => 'Kimia',
            'bg' => '#B8860B',
            'text' => '#FFFFFF',
            'badge' => 'background:#A16207; color:#FFFFFF;',
            'tag' => 'bg-amber-50 text-amber-800 border-amber-300'
        ],
        'FIS' => [
            'name' => 'Fisika',
            'bg' => '#312E81',
            'text' => '#FFFFFF',
            'badge' => 'background:#312E81; color:#FFFFFF;',
            'tag' => 'bg-indigo-50 text-indigo-800 border-indigo-300'
        ],
        'FISIKA' => [
            'name' => 'Fisika',
            'bg' => '#312E81',
            'text' => '#FFFFFF',
            'badge' => 'background:#312E81; color:#FFFFFF;',
            'tag' => 'bg-indigo-50 text-indigo-800 border-indigo-300'
        ],
        'B.INDO' => [
            'name' => 'Bahasa Indonesia',
            'bg' => '#0284C7',
            'text' => '#FFFFFF',
            'badge' => 'background:#0284C7; color:#FFFFFF;',
            'tag' => 'bg-sky-50 text-sky-800 border-sky-300'
        ],
        'B.ING' => [
            'name' => 'Bahasa Inggris',
            'bg' => '#0369A1',
            'text' => '#FFFFFF',
            'badge' => 'background:#0369A1; color:#FFFFFF;',
            'tag' => 'bg-blue-50 text-blue-800 border-blue-300'
        ],
        'MATE' => [
            'name' => 'Matematika',
            'bg' => '#CA8A04',
            'text' => '#FFFFFF',
            'badge' => 'background:#CA8A04; color:#FFFFFF;',
            'tag' => 'bg-yellow-50 text-yellow-800 border-yellow-300'
        ],
        'EKO' => [
            'name' => 'Ekonomi',
            'bg' => '#0891B2',
            'text' => '#FFFFFF',
            'badge' => 'background:#0891B2; color:#FFFFFF;',
            'tag' => 'bg-cyan-50 text-cyan-800 border-cyan-300'
        ],
        'SEJ' => [
            'name' => 'Sejarah',
            'bg' => '#EAB308',
            'text' => '#1F2937',
            'badge' => 'background:#EAB308; color:#1F2937;',
            'tag' => 'bg-yellow-100 text-yellow-900 border-yellow-400'
        ],
        'SOS' => [
            'name' => 'Sosiologi',
            'bg' => '#F43F5E',
            'text' => '#FFFFFF',
            'badge' => 'background:#F43F5E; color:#FFFFFF;',
            'tag' => 'bg-rose-50 text-rose-800 border-rose-300'
        ],
        'GEO' => [
            'name' => 'Geografi',
            'bg' => '#9333EA',
            'text' => '#FFFFFF',
            'badge' => 'background:#9333EA; color:#FFFFFF;',
            'tag' => 'bg-purple-50 text-purple-800 border-purple-300'
        ],
        'MAND' => [
            'name' => 'Bahasa Mandarin',
            'bg' => '#10B981',
            'text' => '#FFFFFF',
            'badge' => 'background:#10B981; color:#FFFFFF;',
            'tag' => 'bg-green-50 text-green-800 border-green-300'
        ],
        'KES' => [
            'name' => 'Kesenian / Seni Budaya',
            'bg' => '#71717A',
            'text' => '#FFFFFF',
            'badge' => 'background:#71717A; color:#FFFFFF;',
            'tag' => 'bg-zinc-100 text-zinc-800 border-zinc-300'
        ],
        'PJOK' => [
            'name' => 'PJOK',
            'bg' => '#EA580C',
            'text' => '#FFFFFF',
            'badge' => 'background:#EA580C; color:#FFFFFF;',
            'tag' => 'bg-orange-50 text-orange-800 border-orange-300'
        ],
        'PAK' => [
            'name' => 'Pendidikan Agama Kristen',
            'bg' => '#DB2777',
            'text' => '#FFFFFF',
            'badge' => 'background:#DB2777; color:#FFFFFF;',
            'tag' => 'bg-pink-50 text-pink-800 border-pink-300'
        ],
        'PPKN' => [
            'name' => 'PPKn',
            'bg' => '#0F172A',
            'text' => '#FFFFFF',
            'badge' => 'background:#0F172A; color:#FFFFFF;',
            'tag' => 'bg-slate-100 text-slate-900 border-slate-400'
        ],
        'TIK' => [
            'name' => 'TIK / Informatika',
            'bg' => '#DC2626',
            'text' => '#FFFFFF',
            'badge' => 'background:#DC2626; color:#FFFFFF;',
            'tag' => 'bg-red-50 text-red-800 border-red-300'
        ],
    ];

    /**
     * Data Roster Pelajaran Lengkap Mingguan (Senin - Sabtu).
     */
    public static function getRosterData(): array
    {
        return [
            'senin' => [
                'day_name' => 'Senin',
                'day_code' => 'senin',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.10',
                        'is_special' => true,
                        'special_title' => 'U P A C A R A',
                        'slots' => [],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.10 - 08.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'X IPAS B' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI A'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI B'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII A'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII B'    => ['subject' => 'SEJ', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.50 - 09.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'X IPAS B' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI A'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI B'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII A'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII B'    => ['subject' => 'SEJ', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.30 - 10.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'X IPAS B' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI A'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI B'     => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XII A'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII B'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '10.10 - 10.30',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.30 - 11.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI A'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI B'     => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XII A'    => ['subject' => 'FISIKA', 'teacher' => 'MT'],
                            'XII B'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '11.10 - 11.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI A'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI B'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XII A'    => ['subject' => 'FISIKA', 'teacher' => 'MT'],
                            'XII B'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                        ],
                    ],
                    [
                        'les' => '7',
                        'time' => '11.50 - 12.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI A'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI B'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XII A'    => ['subject' => 'FISIKA', 'teacher' => 'MT'],
                            'XII B'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '12.30 - 12.40',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '8',
                        'time' => '12.40 - 13.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI A'     => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI B'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII A'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII B'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                        ],
                    ],
                    [
                        'les' => '9',
                        'time' => '13.20 - 14.00',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI A'     => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI B'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII A'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII B'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                        ],
                    ],
                ],
            ],

            'selasa' => [
                'day_name' => 'Selasa',
                'day_code' => 'selasa',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'X IPAS B' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI B'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII A'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII B'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                        ],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.10 - 08.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'X IPAS B' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI B'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII A'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII B'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.50 - 09.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'X IPAS B' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI B'     => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XII A'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII B'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.30 - 10.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'X IPAS B' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI A'     => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XI B'     => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XII A'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII B'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '10.10 - 10.30',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.30 - 11.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'X IPAS B' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI A'     => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XI B'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII A'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII B'    => ['subject' => 'EKO', 'teacher' => 'AK'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '11.10 - 11.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'X IPAS B' => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI A'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI B'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII A'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII B'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                        ],
                    ],
                    [
                        'les' => '7',
                        'time' => '11.50 - 12.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'X IPAS B' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI A'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI B'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII A'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII B'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '12.30 - 12.40',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '8',
                        'time' => '12.40 - 13.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI A'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI B'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII A'    => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XII B'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                        ],
                    ],
                    [
                        'les' => '9',
                        'time' => '13.20 - 14.00',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI A'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XI B'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII A'    => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XII B'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                        ],
                    ],
                ],
            ],

            'rabu' => [
                'day_name' => 'Rabu',
                'day_code' => 'rabu',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'X IPAS B' => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XI A'     => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XI B'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII A'    => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XII B'    => ['subject' => 'GEO', 'teacher' => 'OS'],
                        ],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.10 - 08.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'X IPAS B' => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XI A'     => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XI B'     => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XII A'    => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XII B'    => ['subject' => 'GEO', 'teacher' => 'OS'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.50 - 09.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'X IPAS B' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI A'     => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI B'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XII A'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                            'XII B'    => ['subject' => 'GEO', 'teacher' => 'OS'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.30 - 10.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'X IPAS B' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI A'     => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI B'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XII A'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                            'XII B'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '10.10 - 10.30',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.30 - 11.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'X IPAS B' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XI A'     => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'XI B'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XII A'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                            'XII B'    => ['subject' => 'KIM', 'teacher' => 'RH'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '11.10 - 11.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'X IPAS B' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XI A'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI B'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII A'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII B'    => ['subject' => 'TIK', 'teacher' => 'GR'],
                        ],
                    ],
                    [
                        'les' => '7',
                        'time' => '11.50 - 12.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'X IPAS B' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI A'     => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI B'     => ['subject' => 'KIM', 'teacher' => 'RH'],
                            'XII A'    => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII B'    => ['subject' => 'TIK', 'teacher' => 'GR'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '12.30 - 12.40',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '8',
                        'time' => '12.40 - 13.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'X IPAS B' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI A'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XI B'     => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XII A'    => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII B'    => ['subject' => 'PAK', 'teacher' => 'MR'],
                        ],
                    ],
                    [
                        'les' => '9',
                        'time' => '13.20 - 14.00',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'FIS', 'teacher' => '#REF!'],
                            'X IPAS B' => ['subject' => 'EKO', 'teacher' => 'AK'],
                            'XI A'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XI B'     => ['subject' => 'TIK', 'teacher' => 'GR'],
                            'XII A'    => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII B'    => ['subject' => 'PAK', 'teacher' => 'MR'],
                        ],
                    ],
                ],
            ],

            'kamis' => [
                'day_name' => 'Kamis',
                'day_code' => 'kamis',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.10',
                        'is_special' => true,
                        'special_title' => 'K E B A K T I A N',
                        'slots' => [],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.10 - 08.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI A'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI B'     => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XII A'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XII B'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.50 - 09.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'X IPAS B' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI A'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI B'     => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XII A'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XII B'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.30 - 10.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'X IPAS B' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'XI A'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XI B'     => ['subject' => 'SOS', 'teacher' => 'RW'],
                            'XII A'    => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XII B'    => ['subject' => 'SOS', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '10.10 - 10.30',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.30 - 11.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'X IPAS B' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'XI A'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI B'     => ['subject' => 'SOS', 'teacher' => 'RW'],
                            'XII A'    => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XII B'    => ['subject' => 'SOS', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '11.10 - 11.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'X IPAS B' => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XI A'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI B'     => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XII A'    => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII B'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                        ],
                    ],
                    [
                        'les' => '7',
                        'time' => '11.50 - 12.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'X IPAS B' => ['subject' => 'MAND', 'teacher' => 'RE'],
                            'XI A'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI B'     => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XII A'    => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XII B'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '12.30 - 12.40',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '8',
                        'time' => '12.40 - 13.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'X IPAS B' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI A'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI B'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII A'    => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XII B'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                        ],
                    ],
                    [
                        'les' => '9',
                        'time' => '13.20 - 14.00',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'X IPAS B' => ['subject' => 'BIO', 'teacher' => 'NR'],
                            'XI A'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI B'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XII A'    => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XII B'    => ['subject' => 'MAND', 'teacher' => 'RE'],
                        ],
                    ],
                ],
            ],

            'jumat' => [
                'day_name' => 'Jumat',
                'day_code' => 'jumat',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.05',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'X IPAS B' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI A'     => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XI B'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII A'    => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XII B'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                        ],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.05 - 08.40',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'X IPAS B' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI A'     => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XI B'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII A'    => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XII B'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.40 - 09.15',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'X IPAS B' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'XI A'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI B'     => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XII A'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                            'XII B'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.15 - 09.35',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'X IPAS B' => ['subject' => 'B.ING', 'teacher' => 'IM'],
                            'XI A'     => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'XI B'     => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XII A'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                            'XII B'    => ['subject' => 'SOS', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '09.35 - 10.10',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.10 - 10.45',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'X IPAS B' => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XI A'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XI B'     => ['subject' => 'SOS', 'teacher' => 'RW'],
                            'XII A'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII B'    => ['subject' => 'SOS', 'teacher' => 'AP'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '10.45 - 11.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'MATE', 'teacher' => 'NN'],
                            'X IPAS B' => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XI A'     => ['subject' => 'B.ING', 'teacher' => 'MS'],
                            'XI B'     => ['subject' => 'SOS', 'teacher' => 'RW'],
                            'XII A'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII B'    => ['subject' => 'SOS', 'teacher' => 'AP'],
                        ],
                    ],
                ],
            ],

            'sabtu' => [
                'day_name' => 'Sabtu',
                'day_code' => 'sabtu',
                'rows' => [
                    [
                        'les' => '1',
                        'time' => '07.30 - 08.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI A'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI B'     => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XII A'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII B'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                        ],
                    ],
                    [
                        'les' => '2',
                        'time' => '08.10 - 08.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI A'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI B'     => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XII A'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII B'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                        ],
                    ],
                    [
                        'les' => '3',
                        'time' => '08.50 - 09.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'SOS', 'teacher' => 'AP'],
                            'X IPAS B' => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XI A'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XI B'     => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XII A'    => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII B'    => ['subject' => 'MATE', 'teacher' => 'NH'],
                        ],
                    ],
                    [
                        'les' => '4',
                        'time' => '09.30 - 10.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'X IPAS B' => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XI A'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI B'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII A'    => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XII B'    => ['subject' => 'GEO', 'teacher' => 'OS'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '10.10 - 10.30',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '5',
                        'time' => '10.30 - 11.10',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'X IPAS B' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XI B'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII A'    => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XII B'    => ['subject' => 'GEO', 'teacher' => 'OS'],
                        ],
                    ],
                    [
                        'les' => '6',
                        'time' => '11.10 - 11.50',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'X IPAS B' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XI B'     => ['subject' => 'PJOK', 'teacher' => 'ZV'],
                            'XII A'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII B'    => ['subject' => 'PPKN', 'teacher' => 'WH'],
                        ],
                    ],
                    [
                        'les' => '7',
                        'time' => '11.50 - 12.30',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'X IPAS B' => ['subject' => 'SEJ', 'teacher' => 'AP'],
                            'XI A'     => ['subject' => 'PAK', 'teacher' => 'MR'],
                            'XI B'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XII A'    => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'XII B'    => ['subject' => 'PPKN', 'teacher' => 'WH'],
                        ],
                    ],
                    [
                        'les' => '—',
                        'time' => '12.30 - 12.40',
                        'is_break' => true,
                        'break_title' => 'I S T I R A H A T',
                        'slots' => [],
                    ],
                    [
                        'les' => '8',
                        'time' => '12.40 - 13.20',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'X IPAS B' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XI A'     => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XI B'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XII A'    => null,
                            'XII B'    => null,
                        ],
                    ],
                    [
                        'les' => '9',
                        'time' => '13.20 - 14.00',
                        'is_special' => false,
                        'slots' => [
                            'X IPAS A' => ['subject' => 'B.INDO', 'teacher' => 'RZ'],
                            'X IPAS B' => ['subject' => 'GEO', 'teacher' => 'OS'],
                            'XI A'     => ['subject' => 'PPKN', 'teacher' => 'WH'],
                            'XI B'     => ['subject' => 'KES', 'teacher' => 'DN'],
                            'XII A'    => null,
                            'XII B'    => null,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Dapatkan jadwal khusus untuk seorang guru (berdasarkan kode guru atau nama).
     */
    public static function getScheduleForTeacher(string $teacherCode): array
    {
        $all = self::getRosterData();
        $teacherSchedule = [];

        foreach ($all as $dayKey => $dayData) {
            $slotsForDay = [];
            foreach ($dayData['rows'] as $row) {
                if (!empty($row['is_break']) || !empty($row['is_special'])) {
                    continue;
                }
                foreach ($row['slots'] as $className => $slot) {
                    if ($slot && $slot['teacher'] === $teacherCode) {
                        $slotsForDay[] = [
                            'les' => $row['les'],
                            'time' => $row['time'],
                            'class' => $className,
                            'subject' => $slot['subject'],
                            'teacher' => $slot['teacher'],
                        ];
                    }
                }
            }
            if (!empty($slotsForDay)) {
                $teacherSchedule[$dayKey] = [
                    'day_name' => $dayData['day_name'],
                    'items' => $slotsForDay,
                ];
            }
        }

        return $teacherSchedule;
    }

    /**
     * Dapatkan jadwal hari ini untuk seorang guru.
     */
    public static function getTodayScheduleForTeacher(string $teacherCode): array
    {
        $dayMap = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'senin', // Minggu default ke senin
        ];
        $currentDayCode = $dayMap[(int) date('N')] ?? 'senin';
        $fullTeacherSchedule = self::getScheduleForTeacher($teacherCode);

        return [
            'day_code' => $currentDayCode,
            'day_name' => ucfirst($currentDayCode),
            'items' => $fullTeacherSchedule[$currentDayCode]['items'] ?? [],
        ];
    }
}
