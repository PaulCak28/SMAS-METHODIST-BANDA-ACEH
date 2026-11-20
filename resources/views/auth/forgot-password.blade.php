@php
    // Nomor admin diambil dari .env melalui config/services.php.
    // Simpan dalam format internasional, hanya angka (contoh: 62812xxxxxxx).
    $adminNumber = preg_replace('/\D+/', '', (string) config('services.whatsapp.admin_number', ''));
    $whatsappMessage = 'Halo Admin, saya lupa password akun sekolah saya. Mohon untuk konfirmasinya dan bantuan reset password. Terima kasih.';
    $whatsappUrl = $adminNumber !== ''
        ? 'https://wa.me/' . $adminNumber . '?text=' . rawurlencode($whatsappMessage)
        : null;
@endphp

<x-login-layout>
    <section class="sl-card" aria-labelledby="forgot-password-title">
        <div class="sl-card-head">
            <h1 id="forgot-password-title" class="sl-welcome">Lupa Kata Sandi?</h1>
            <p class="sl-welcome-sub">
                Jangan khawatir. Jika Anda lupa kata sandi akun sekolah, silakan
                hubungi administrator sekolah melalui WhatsApp untuk mendapatkan bantuan.
            </p>
        </div>

        @if ($whatsappUrl)
            <a
                href="{{ $whatsappUrl }}"
                class="sl-btn"
                style="text-decoration: none; text-transform: none; letter-spacing: 0.01em; line-height: 1.45; text-align: center;"
                target="_blank"
                rel="noopener noreferrer"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.5 11.5a8.4 8.4 0 0 1-12.4 7.4L3 20l1.2-4.8A8.4 8.4 0 1 1 20.5 11.5Z"/>
                    <path d="M8.7 8.2c.2-.4.4-.4.7-.4h.5c.2 0 .4.1.5.4l.7 1.6c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4-.1.6.5.9 1.2 1.6 2.1 2.1.2.1.4.1.6-.1l.6-.5c.2-.2.4-.2.6-.1l1.6.7c.3.1.4.3.4.5 0 .3-.1.8-.4 1.1-.4.4-1 .7-1.7.7-.7 0-1.8-.3-3.1-1.1-1.6-1-2.7-2.4-3.2-3.3-.5-.9-.7-1.7-.7-2.3 0-.6.3-1.2.7-1.6Z"/>
                </svg>
                Hubungi Admin melalui WhatsApp
            </a>

            <div class="sl-help" role="note">
                <svg class="sl-help-ic" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>
                    WhatsApp akan terbuka dengan pesan otomatis bahwa Anda lupa kata sandi
                    dan membutuhkan bantuan administrator.
                </span>
            </div>
        @else
            <div class="sl-help" role="status">
                <svg class="sl-help-ic" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>
                    Kontak WhatsApp administrator belum diatur. Silakan hubungi pihak sekolah
                    untuk mendapatkan bantuan reset kata sandi.
                </span>
            </div>
        @endif

        <div class="sl-divider"></div>
        <p class="sl-copy">
            <a class="sl-link" href="{{ route('login') }}">Kembali ke halaman masuk</a>
        </p>
        <p class="sl-copy">&copy; {{ date('Y') }} {{ config('school.name', 'SMAS Methodist Banda Aceh') }}</p>
    </section>
</x-login-layout>
