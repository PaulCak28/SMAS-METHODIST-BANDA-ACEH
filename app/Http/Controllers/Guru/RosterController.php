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
        // Budi Santoso mengajar Matematika (NN) dan Geografi (OS)
        $userName = strtolower($user?->name ?? '');
        $currentTeacherCodes = [];
        if (str_contains($userName, 'budi')) {
            $currentTeacherCodes = ['NN', 'OS'];
        } elseif (str_contains($userName, 'siti')) {
            $currentTeacherCodes = ['MT', 'WH'];
        } elseif (str_contains($userName, 'andi')) {
            $currentTeacherCodes = ['NR'];
        } elseif (str_contains($userName, 'rina')) {
            $currentTeacherCodes = ['RH', 'GR'];
        } elseif (str_contains($userName, 'maria')) {
            $currentTeacherCodes = ['RZ'];
        } elseif (str_contains($userName, 'john')) {
            $currentTeacherCodes = ['MS', 'ZV'];
        } elseif (str_contains($userName, 'dewi')) {
            $currentTeacherCodes = ['AP'];
        } elseif (str_contains($userName, 'daniel')) {
            $currentTeacherCodes = ['AK'];
        } elseif (str_contains($userName, 'nasrul')) {
            $currentTeacherCodes = ['NH'];
        }

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
