/**
 * file-validator.js  ->  simpan di public/js/file-validator.js
 *
 * Otomatis aktif di setiap <input type="file" data-allowed="pdf,docx,..." data-max-mb="20">
 * Yang dicek:
 *   1. Ekstensi termasuk format yang diizinkan
 *   2. ISI file (magic bytes) cocok dengan ekstensinya
 *      (mencegah file .exe/.php yang cuma di-rename jadi .pdf)
 *   3. Ukuran file (opsional)
 * Jika gagal: pesan error tampil dan pilihan file dikosongkan.
 *
 * Catatan: ini hanya validasi sisi browser. Validasi server (mimes) tetap wajib.
 */
(function () {
    const ALIAS = { jpeg: 'jpg' };

    const startsWith = (b, sig, off = 0) => sig.every((v, i) => b[off + i] === v);
    const ascii = (b) => { let s = ''; for (let i = 0; i < b.length; i++) s += String.fromCharCode(b[i]); return s; };

    /** Tebak jenis file dari isinya. */
    async function detect(file) {
        const b = new Uint8Array(await file.slice(0, 512 * 1024).arrayBuffer());
        if (b.length === 0) return 'empty';
        if (startsWith(b, [0x25, 0x50, 0x44, 0x46])) return 'pdf';
        if (startsWith(b, [0x89, 0x50, 0x4E, 0x47])) return 'png';
        if (startsWith(b, [0xFF, 0xD8, 0xFF])) return 'jpg';
        if (startsWith(b, [0xD0, 0xCF, 0x11, 0xE0, 0xA1, 0xB1, 0x1A, 0xE1])) return 'ole'; // doc/xls/ppt lama
        if (startsWith(b, [0x50, 0x4B, 0x03, 0x04])) {                                   // zip & turunan OOXML
            const t = ascii(b);
            if (t.includes('word/')) return 'docx';
            if (t.includes('xl/')) return 'xlsx';
            if (t.includes('ppt/')) return 'pptx';
            return 'zip';
        }
        if (b.length >= 8 && ascii(b.slice(4, 8)) === 'ftyp') return 'mp4';
        if (!b.slice(0, 4096).includes(0)) return 'txt'; // tanpa byte NUL -> teks biasa
        return 'unknown';
    }

    /** Apakah jenis hasil deteksi cocok dengan ekstensi? */
    function matches(ext, kind) {
        switch (ext) {
            case 'doc': case 'xls': case 'ppt': return kind === 'ole';
            case 'docx': case 'xlsx': case 'pptx': return kind === ext;
            case 'zip': return ['zip', 'docx', 'xlsx', 'pptx'].includes(kind);
            default: return kind === ext; // pdf, png, jpg, mp4, txt
        }
    }

    function errorBox(input) {
        let box = input.parentElement.querySelector('[data-file-error]');
        if (!box) {
            box = document.createElement('p');
            box.setAttribute('data-file-error', '');
            box.className = 'mt-1 text-xs font-semibold text-rose-600';
            input.insertAdjacentElement('afterend', box);
        }
        return box;
    }

    async function check(input) {
        const box = errorBox(input);
        box.textContent = '';
        const file = input.files && input.files[0];
        if (!file) return true;

        const allowed = (input.dataset.allowed || '').split(',').map(s => s.trim().toLowerCase()).filter(Boolean);
        const maxMb = parseFloat(input.dataset.maxMb || '0');
        const ext = (ALIAS[file.name.split('.').pop().toLowerCase()]) || file.name.split('.').pop().toLowerCase();

        let msg = '';
        if (allowed.length && !allowed.includes(ext)) {
            msg = 'Format .' + ext + ' tidak diizinkan. Gunakan: ' + allowed.join(', ').toUpperCase() + '.';
        } else if (maxMb && file.size > maxMb * 1024 * 1024) {
            msg = 'Ukuran file melebihi ' + maxMb + ' MB.';
        } else {
            const kind = await detect(file);
            if (kind === 'empty') msg = 'File kosong.';
            else if (!matches(ext, kind)) msg = 'Isi file tidak sesuai dengan ekstensi .' + ext + '. File mungkin rusak atau hanya diganti namanya.';
        }

        if (msg) {
            box.textContent = msg;
            input.value = '';
            return false;
        }
        return true;
    }

    document.addEventListener('change', (e) => {
        const el = e.target;
        if (el instanceof HTMLInputElement && el.type === 'file' && el.hasAttribute('data-allowed')) check(el);
    });

    window.validateUploadInput = check; // bisa dipanggil manual bila perlu
})();