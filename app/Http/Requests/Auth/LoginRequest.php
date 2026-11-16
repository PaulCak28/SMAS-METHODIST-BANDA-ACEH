<?php

namespace App\Http\Requests\Auth;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Identitas fleksibel: boleh NIS (siswa), NIP (guru), atau email (admin).
        // Karena bukan email saja, aturan 'email' dilepas dan cukup 'string'.
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Bangun kredensial login fleksibel berdasarkan identitas yang dimasukkan.
     *
     * - Jika input berformat email  -> dicocokkan ke kolom `email` (umumnya admin).
     * - Selain itu (NIS / NIP)       -> dicocokkan ke kolom `user_id`, yaitu primary key
     *   tabel `users` yang memang diisi dengan NIS siswa / NIP guru.
     *
     * Password tetap diverifikasi oleh guard Laravel terhadap hash yang tersimpan.
     *
     * @return array<string, string>
     */
    protected function credentials(): array
    {
        $identity = (string) $this->input('email');
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_id';

        return [
            $field => $identity,
            'password' => (string) $this->input('password'),
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->credentials(), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Tolak akun yang statusnya tidak aktif (siswa lulus/pindah/drop_out,
        // guru cuti/pensiun/resign). Admin & akun tanpa data status dilewatkan.
        if (! $this->accountIsActive(Auth::user())) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator sekolah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Status yang dianggap aktif untuk bisa login. Ubah di satu tempat ini bila
     * kebijakan sekolah berbeda (mis. izinkan guru 'cuti' tetap masuk).
     */
    protected const ACTIVE_STATUSES = ['aktif', 'cuti'];

    /**
     * Periksa apakah akun boleh login berdasarkan status siswa/guru di database.
     *
     * Bersifat fail-open: hanya memblokir ketika benar-benar ditemukan status
     * yang tidak aktif. Admin/orang tua, atau akun tanpa baris status terkait,
     * tetap diizinkan agar perubahan ini tidak mengunci pengguna yang sah.
     */
    protected function accountIsActive(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->role === 'siswa') {
            $status = Student::whereKey($user->user_id)->value('status');

            return $status === null || in_array($status, self::ACTIVE_STATUSES, true);
        }

        if ($user->role === 'guru') {
            $status = Teacher::whereKey($user->user_id)->value('status');

            return $status === null || in_array($status, self::ACTIVE_STATUSES, true);
        }

        return true;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}