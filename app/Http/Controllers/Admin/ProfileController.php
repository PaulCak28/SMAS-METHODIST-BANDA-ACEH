<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil admin.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Hitung pengumuman yang dibuat oleh admin ini jika ada
        $totalAnnouncements = DB::connection('mysql_admin')
            ->table('announcements')
            ->where('created_by', $user->user_id)
            ->count();

        return view('admin.profile.index', compact('user', 'totalAnnouncements'));
    }

    /**
     * Perbarui informasi profil admin (nama, email, no telepon, foto).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('mysql_admin.users', 'email')->ignore($currentUser->user_id, 'user_id'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
            'phone.max'      => 'Nomor telepon maksimal 20 karakter.',
            'photo.image'    => 'Berkas foto harus berupa gambar.',
            'photo.mimes'    => 'Format foto harus berupa JPG, PNG, atau WEBP.',
            'photo.max'      => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        try {
            $user = User::on('mysql_admin')->where('user_id', $currentUser->user_id)->firstOrFail();

            $user->name  = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;

            // Handle upload foto profil
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada di storage
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $path = $request->file('photo')->store('profiles', 'public');
                $user->photo = $path;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return redirect()->route('admin.profile.index')
                ->with('success', 'Profil admin berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Perbarui kata sandi admin.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini yang Anda masukkan salah.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
            'password.min'                      => 'Password baru minimal harus 8 karakter.',
        ]);

        try {
            $currentUser = $request->user();
            $user = User::on('mysql_admin')->where('user_id', $currentUser->user_id)->firstOrFail();

            $user->password = Hash::make($validated['password']);
            $user->save();

            return redirect()->route('admin.profile.index')
                ->with('password_success', 'Kata sandi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui kata sandi: ' . $e->getMessage());
        }
    }
}
