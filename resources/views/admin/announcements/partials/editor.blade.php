<div class="mb-4">
    <label for="announcement-content-editor" class="form-label fw-semibold">
        Isi Pengumuman <span class="text-danger">*</span>
    </label>

    <div id="announcement-content-editor" class="bg-white @error('content') border border-danger @enderror"></div>
    <textarea id="announcement-content" name="content" class="visually-hidden">{{ $content }}</textarea>

    <div id="announcement-content-upload-status" class="small text-muted mt-2 d-none" role="status">
        <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
        Mengunggah gambar...
    </div>

    <small class="text-muted d-block mt-2">
        Gunakan toolbar untuk memformat teks, menambahkan tautan, atau mengunggah gambar (JPG, PNG, WebP; maksimal 5 MB).
    </small>

    @error('content')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

@pushOnce('styles', 'announcement-editor-styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        #announcement-content-editor {
            min-height: 280px;
        }

        #announcement-content-editor .ql-editor {
            min-height: 280px;
            font-size: 1rem;
            line-height: 1.7;
        }

        #announcement-content-editor .ql-editor img {
            max-width: 100%;
            height: auto;
        }

        .ql-toolbar.ql-snow {
            border-radius: 0.5rem 0.5rem 0 0;
            background: var(--bs-light);
        }

        .ql-container.ql-snow {
            border-radius: 0 0 0.5rem 0.5rem;
        }
    </style>
@endPushOnce

@pushOnce('scripts', 'announcement-editor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contentInput = document.getElementById('announcement-content');
            const uploadStatus = document.getElementById('announcement-content-upload-status');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const quill = new Quill('#announcement-content-editor', {
                theme: 'snow',
                placeholder: 'Tulis isi pengumuman di sini...',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['link', 'image'],
                            ['clean'],
                        ],
                    },
                },
            });

            if (contentInput.value.trim() !== '') {
                quill.clipboard.dangerouslyPasteHTML(contentInput.value);
            }

            const synchronizeContent = () => {
                contentInput.value = quill.root.innerHTML;
            };

            quill.on('text-change', synchronizeContent);
            synchronizeContent();

            quill.getModule('toolbar').addHandler('image', () => {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/jpeg,image/png,image/webp';

                fileInput.addEventListener('change', async () => {
                    const image = fileInput.files?.[0];

                    if (! image) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('image', image);
                    uploadStatus.classList.remove('d-none');

                    try {
                        const response = await fetch(@js(route('admin.announcements.images.store')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: formData,
                        });
                        const payload = await response.json();

                        if (! response.ok) {
                            throw new Error(payload.errors?.image?.[0] ?? 'Gambar gagal diunggah.');
                        }

                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', payload.url, 'user');
                        quill.setSelection(range.index + 1, 0, 'silent');
                        synchronizeContent();
                    } catch (error) {
                        window.alert(error.message ?? 'Gambar gagal diunggah. Silakan coba lagi.');
                    } finally {
                        uploadStatus.classList.add('d-none');
                    }
                });

                fileInput.click();
            });

            contentInput.form.addEventListener('submit', synchronizeContent);
        });
    </script>
@endPushOnce
