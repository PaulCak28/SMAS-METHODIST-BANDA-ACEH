@extends('layouts.admin')

@section('title', 'Enrollment Siswa')

@section('content')
@php
    // Daftar kelas-mapel (class_subject_teacher) untuk modal "Masukkan Satu Kelas".
    // Dikirim dari ClassEnrollmentController@index.
    $csts = $csts ?? collect();
    $cstGroups = $csts->groupBy(fn ($cst) => $cst->schoolClass->name ?? 'Tanpa Kelas');

    // Siswa per kelas induk (key: class_id) dan siswa yang sudah terdaftar per kelas-mapel (key: cst_id).
    $studentsByClass = $studentsByClass ?? collect();
    $enrolledByCst = $enrolledByCst ?? collect();
    $studentPayload = $studentsByClass->map(fn ($list) => $list->map(fn ($s) => [
        'nis' => $s->nis,
        'name' => $s->user->name ?? 'Tanpa Nama',
    ])->values());
@endphp

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Enrollment Kelas Siswa</h4>
        <p class="text-muted small mb-0">Kelola pendaftaran kelas mata pelajaran per siswa.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-success shadow-sm rounded-3 fw-medium px-3" data-bs-toggle="modal" data-bs-target="#bulkEnrollModal">
            <i class="bi bi-people-fill me-1"></i> Masukkan Satu Kelas
        </button>
        <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary shadow-sm rounded-3 fw-medium px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Enrollment Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0 bg-success text-white" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.enrollments.index') }}" method="GET" class="row g-3">
            <div class="col-md-9">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 fs-6" placeholder="Cari nama siswa atau NIS..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-medium btn-lg">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3">NIS</th>
                    <th class="py-3">Nama Siswa</th>
                    <th class="py-3">Kelas Induk (Ruangan)</th>
                    <th class="py-3 text-center">Jml Kelas Mapel</th>
                    <th class="text-end pe-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="ps-4 font-monospace text-muted">{{ $student->nis }}</td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $student->user->name ?? 'Tanpa Nama' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark border">{{ $student->schoolClass->name ?? 'Belum ada' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-bold">
                                {{ $student->classEnrollments->count() }} Kelas
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.enrollments.show', $student->nis) }}" class="btn btn-sm btn-light border shadow-sm text-primary px-3 rounded-3 fw-medium">
                                <i class="bi bi-eye me-1"></i> Kelola Kelas <i class="bi bi-chevron-right ms-1 small"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                            <h6 class="fw-semibold text-dark">Belum ada data enrollment</h6>
                            <p class="small mb-0">Silakan tambahkan pendaftaran kelas untuk siswa.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $students->links() }}
        </div>
    @endif
</div>

{{-- Modal: masukkan seluruh siswa dalam satu kelas induk ke satu kelas mapel --}}
<div class="modal fade" id="bulkEnrollModal" tabindex="-1" aria-labelledby="bulkEnrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('admin.enrollments.store-bulk') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="bulkEnrollModalLabel">Masukkan Satu Kelas</h5>
                        <p class="text-muted small mb-0">Daftarkan seluruh siswa dalam satu kelas ke sebuah kelas mata pelajaran.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body px-4">
                    @if($errors->hasAny(['cst_id', 'students_id']))
                        <div class="alert alert-danger small rounded-3 py-2">
                            {{ $errors->first('cst_id') ?: $errors->first('students_id') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="bulk_cst_id" class="form-label fw-semibold">Kelas &amp; Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="cst_id" id="bulk_cst_id" class="form-select bg-light" required>
                            <option value="" disabled {{ old('cst_id') ? '' : 'selected' }}>Pilih kelas &amp; mata pelajaran...</option>
                            @foreach($cstGroups as $className => $items)
                                <optgroup label="{{ $className }}">
                                    @foreach($items as $cst)
                                        <option value="{{ $cst->cst_id }}" data-class-id="{{ $cst->class_id }}" {{ (string) old('cst_id') === (string) $cst->cst_id ? 'selected' : '' }}>
                                            {{ $className }} — {{ $cst->subject->name ?? $cst->subject->subject_name ?? 'Mapel' }}
                                            @if(!empty($cst->academic_year)) ({{ $cst->academic_year }}) @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div id="bulkStudentsWrapper" class="mb-3 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">
                                Siswa yang didaftarkan
                                <span id="bulkCount" class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1"></span>
                            </label>
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" id="bulkCheckAll" checked>
                                <label class="form-check-label small" for="bulkCheckAll">Pilih semua</label>
                            </div>
                        </div>
                        <div id="bulkStudentList" class="border rounded-3 bg-light p-2" style="max-height: 260px; overflow-y: auto;"></div>
                    </div>

                    <div id="bulkEmpty" class="alert alert-warning small rounded-3 py-2 d-none">
                        Tidak ada siswa di kelas induk ini.
                    </div>

                    @if($csts->isEmpty())
                        <div class="alert alert-warning small rounded-3 py-2 mb-0">
                            Belum ada kelas mata pelajaran. Tambahkan dulu data kelas-mapel.
                        </div>
                    @else
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Siswa terdaftar dengan status <strong>Aktif</strong>. Hilangkan centang untuk siswa yang tidak ingin didaftarkan.
                        </div>
                    @endif
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light border px-4 rounded-3 fw-medium" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 rounded-3 fw-medium">
                        <i class="bi bi-people-fill me-1"></i> Daftarkan Siswa Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var students = @json($studentPayload);
        var enrolled = @json($enrolledByCst);

        var select = document.getElementById('bulk_cst_id');
        var wrapper = document.getElementById('bulkStudentsWrapper');
        var list = document.getElementById('bulkStudentList');
        var count = document.getElementById('bulkCount');
        var checkAll = document.getElementById('bulkCheckAll');
        var empty = document.getElementById('bulkEmpty');

        function enabledBoxes() {
            return list.querySelectorAll('.bulk-student:not(:disabled)');
        }

        function updateCount() {
            var boxes = enabledBoxes();
            var checked = list.querySelectorAll('.bulk-student:not(:disabled):checked').length;
            count.textContent = checked + ' dari ' + boxes.length + ' dipilih';
            checkAll.checked = boxes.length > 0 && checked === boxes.length;
        }

        function render() {
            var option = select.options[select.selectedIndex];
            list.innerHTML = '';

            if (!option || !option.dataset.classId) {
                wrapper.classList.add('d-none');
                empty.classList.add('d-none');
                return;
            }

            var items = students[option.dataset.classId] || [];
            var done = (enrolled[option.value] || []).map(String);

            if (!items.length) {
                wrapper.classList.add('d-none');
                empty.classList.remove('d-none');
                return;
            }

            empty.classList.add('d-none');
            wrapper.classList.remove('d-none');

            items.forEach(function (student, index) {
                var already = done.indexOf(String(student.nis)) !== -1;

                var row = document.createElement('div');
                row.className = 'd-flex align-items-center gap-2 py-1 px-2';

                var input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'form-check-input mt-0 flex-shrink-0 bulk-student';
                input.name = 'students_id[]';
                input.value = student.nis;
                input.id = 'bulk_student_' + index;
                input.checked = !already;
                input.disabled = already;

                var label = document.createElement('label');
                label.className = 'small flex-grow-1 mb-0' + (already ? ' text-muted' : '');
                label.htmlFor = input.id;
                label.textContent = student.nis + ' — ' + student.name;

                row.appendChild(input);
                row.appendChild(label);

                if (already) {
                    var badge = document.createElement('span');
                    badge.className = 'badge bg-secondary-subtle text-secondary border';
                    badge.textContent = 'Sudah terdaftar';
                    row.appendChild(badge);
                }

                list.appendChild(row);
            });

            updateCount();
        }

        select.addEventListener('change', render);
        list.addEventListener('change', updateCount);
        checkAll.addEventListener('change', function () {
            enabledBoxes().forEach(function (box) { box.checked = checkAll.checked; });
            updateCount();
        });

        if (select.value) {
            render();
        }

        @if($errors->hasAny(['cst_id', 'students_id']))
            var modalElement = document.getElementById('bulkEnrollModal');
            if (modalElement && window.bootstrap) {
                new bootstrap.Modal(modalElement).show();
            }
        @endif
    });
</script>
@endsection