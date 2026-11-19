<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $student = $user->student;
        $teacher = $user->teacher;

        $isStudent = $student !== null;
        $profile = $isStudent ? $student : $teacher;

        $stats = [
            'classes_count' => 0,
            'secondary_count' => 0,
            'tertiary_count' => 0,
        ];

        $classes = collect();

        if ($isStudent && $student) {
            $student->load(['schoolClass']);
            $classes = $student->joinedClasses()
                ->with(['subject', 'schoolClass', 'teacher.user'])
                ->get();

            $stats = [
                'classes_count' => $classes->count(),
                'submissions_count' => $student->submissions()->count(),
                'grades_count' => $student->grades()->count(),
            ];
        } elseif ($teacher) {
            $teacher->load(['homeroomClasses']);
            $classes = $teacher->classSubjectTeachers()
                ->with(['subject', 'schoolClass'])
                ->withCount('joinedStudents')
                ->get();

            $cstIds = $classes->pluck('cst_id');
            $stats = [
                'classes_count' => $classes->count(),
                'students_count' => $classes->sum('joined_students_count'),
                'assignments_count' => \App\Models\Assignment::whereIn('cst_id', $cstIds)->count(),
            ];
        }

        return view('profile.edit', compact(
            'user',
            'profile',
            'isStudent',
            'stats',
            'classes'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
