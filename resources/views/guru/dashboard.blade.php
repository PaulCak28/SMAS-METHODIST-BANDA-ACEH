<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="{{ route('guru.classes.index') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('E-Class') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Buka kelas untuk mengelola pembelajaran.') }}</p>
                </a>
                <a href="{{ route('guru.exams.index') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('E-Exam') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Kelola ujian dan kuis kelas Anda.') }}</p>
                </a>
            </div>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Ringkasan Kelas</h3>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    @forelse($overview as $class)
                        @php($classGrades = $gradeSummaries->get($class->cst_id, collect()))
                        <article class="rounded-xl border border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900">{{ $class->class_name }} · {{ $class->subject_name }}</h4>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $class->student_count }} siswa · {{ $class->assignment_count }} tugas ·
                                {{ $class->exam_count }} ujian ({{ $class->published_exam_count }} terbit) ·
                                {{ $class->ungraded_essays }} esai menunggu koreksi
                            </p>
                            <div class="mt-3 space-y-1">
                                @forelse($classGrades->take(5) as $studentGrade)
                                    <div class="flex justify-between gap-3 text-sm text-gray-700">
                                        <span>{{ $studentGrade->student_name }}</span>
                                        <span>{{ $studentGrade->rata_rata ?? '-' }} · {{ $studentGrade->predikat ?? '-' }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada rekap nilai siswa.</p>
                                @endforelse
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada kelas yang diampu.</p>
                    @endforelse
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Status Tugas</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($assignmentStatuses as $assignment)
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-2 text-sm">
                                <span>{{ $assignment->title }} · {{ $assignment->class_name }}</span>
                                <span class="shrink-0 text-gray-600">{{ $assignment->graded_count }}/{{ $assignment->submitted_count }} dinilai</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada tugas.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Jadwal Mengajar</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($schedule as $slot)
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-2 text-sm">
                                <span>{{ ucfirst($slot->day) }} · {{ $slot->class_name }} · {{ $slot->subject_name }}</span>
                                <span class="shrink-0 text-gray-600">{{ substr($slot->start_time, 0, 5) }}–{{ substr($slot->end_time, 0, 5) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada jadwal mengajar.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Siswa Kelas</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($roster as $cstId => $students)
                            <div class="border-b border-gray-100 pb-2">
                                <p class="mb-1 text-sm font-semibold text-gray-800">{{ $students->first()->class_name }} · {{ $students->first()->subject_name }}</p>
                                <p class="text-sm text-gray-600">{{ $students->pluck('student_name')->join(', ') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada siswa yang terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Nilai Terbaru</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($gradebook->take(10) as $grade)
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-2 text-sm">
                                <span>{{ $grade->student_name }} · {{ $grade->source_title }}</span>
                                <span class="shrink-0 text-gray-600">{{ $grade->score }} ({{ $grade->predicate }})</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada nilai.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
