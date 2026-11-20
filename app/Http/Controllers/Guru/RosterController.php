<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Services\RosterService;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $rosterData = RosterService::getRosterData();
        $classes = RosterService::CLASSES;
        $teachers = RosterService::TEACHERS;
        $subjectStyles = RosterService::SUBJECT_STYLES;

        // Tentukan kode guru yang terkait dengan user saat ini
        $userName = strtolower($user?->name ?? '');
        $currentTeacherCodes = [];
        if (str_contains($userName, 'nasrul')) {
            $currentTeacherCodes = ['NH'];
        } elseif (str_contains($userName, 'zakiyul')) {
            $currentTeacherCodes = ['ZV'];
        } elseif (str_contains($userName, 'merry') || str_contains($userName, 'rouli')) {
            $currentTeacherCodes = ['MR'];
        } elseif (str_contains($userName, 'daniatul') || str_contains($userName, 'hikmah')) {
            $currentTeacherCodes = ['DN'];
        } elseif (str_contains($userName, 'mulyadi')) {
            $currentTeacherCodes = ['MS'];
        } elseif (str_contains($userName, 'ajeng')) {
            $currentTeacherCodes = ['AP'];
        } elseif (str_contains($userName, 'rahmat')) {
            $currentTeacherCodes = ['RH'];
        } elseif (str_contains($userName, 'nisrina')) {
            $currentTeacherCodes = ['NR'];
        } elseif (str_contains($userName, 'reza')) {
            $currentTeacherCodes = ['RZ'];
        } elseif (str_contains($userName, 'toha')) {
            $currentTeacherCodes = ['MT'];
        } elseif (str_contains($userName, 'akbarul')) {
            $currentTeacherCodes = ['AK'];
        } elseif (str_contains($userName, 'oslen')) {
            $currentTeacherCodes = ['OS'];
        } elseif (str_contains($userName, 'husna') || str_contains($userName, 'nurul')) {
            $currentTeacherCodes = ['NN'];
        } elseif (str_contains($userName, 'irhamni')) {
            $currentTeacherCodes = ['IM'];
        } elseif (str_contains($userName, 'rayyan')) {
            $currentTeacherCodes = ['RW'];
        } elseif (str_contains($userName, 'wahyu')) {
            $currentTeacherCodes = ['WH'];
        } elseif (str_contains($userName, 'reinaldo')) {
            $currentTeacherCodes = ['RE'];
        } elseif (str_contains($userName, 'geore') || str_contains($userName, 'george') || str_contains($userName, 'rendy')) {
            $currentTeacherCodes = ['GR'];
        } elseif (str_contains($userName, 'budi')) {
            // Demo account Budi Santoso (Matematika & Geografi)
            $currentTeacherCodes = ['NN', 'OS'];
        } elseif (str_contains($userName, 'siti')) {
            $currentTeacherCodes = ['MT', 'WH'];
        } elseif (str_contains($userName, 'andi')) {
            $currentTeacherCodes = ['NR'];
        } elseif (str_contains($userName, 'rina')) {
            $currentTeacherCodes = ['RH'];
        } elseif (str_contains($userName, 'maria')) {
            $currentTeacherCodes = ['RZ'];
        } elseif (str_contains($userName, 'john')) {
            $currentTeacherCodes = ['MS', 'ZV'];
        } elseif (str_contains($userName, 'dewi')) {
            $currentTeacherCodes = ['AP'];
        } elseif (str_contains($userName, 'daniel')) {
            $currentTeacherCodes = ['AK'];
        }

        $teacherDuties = RosterService::TEACHER_DUTIES;

        // Filter aktif
        $activeDay = $request->query('day', 'all');
        $activeClass = $request->query('class', 'all');
        $highlightTeacher = $request->query('teacher', '');

        // Hitung jadwal khusus guru yang login
        $mySchedule = [];
        foreach ($currentTeacherCodes as $code) {
            $schedule = RosterService::getScheduleForTeacher($code);
            foreach ($schedule as $dayKey => $data) {
                if (!isset($mySchedule[$dayKey])) {
                    $mySchedule[$dayKey] = [
                        'day_name' => $data['day_name'],
                        'items' => [],
                    ];
                }
                foreach ($data['items'] as $item) {
                    $mySchedule[$dayKey]['items'][] = $item;
                }
            }
        }

        // Urutkan item per hari berdasarkan les
        foreach ($mySchedule as $dayKey => &$dayGroup) {
            usort($dayGroup['items'], fn($a, $b) => (int)$a['les'] <=> (int)$b['les']);
        }
        unset($dayGroup);

        // Metadata dokumen resmi
        $metadata = [
            'academic_year'   => RosterService::ACADEMIC_YEAR,
            'school_name'     => RosterService::SCHOOL_NAME,
            'principal_name'  => RosterService::PRINCIPAL_NAME,
            'curriculum_head' => RosterService::CURRICULUM_HEAD,
            'decree_date'     => RosterService::DECREE_DATE,
        ];

        return view('guru.roster.index', compact(
            'rosterData',
            'classes',
            'teachers',
            'teacherDuties',
            'subjectStyles',
            'currentTeacherCodes',
            'mySchedule',
            'activeDay',
            'activeClass',
            'highlightTeacher',
            'metadata'
        ));
    }
}
