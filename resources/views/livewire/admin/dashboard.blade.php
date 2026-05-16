<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-medium">Dashboard</h1>
        <p class="text-sm text-zinc-500 mt-1">
            School Year:
            <span class="font-medium text-zinc-700 dark:text-zinc-200">
                {{ $activeSchoolYear?->name ?? 'No active school year' }}
            </span>
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Total Students</p>
            <p class="text-3xl font-medium text-zinc-800 dark:text-zinc-100">{{ $totalStudents }}</p>
            <p class="text-xs text-zinc-400 mt-1">All registered students</p>
        </div>

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Enrolled This Year</p>
            <p class="text-3xl font-medium text-blue-600 dark:text-blue-400">{{ $enrolledThisYear }}</p>
            <p class="text-xs text-zinc-400 mt-1">{{ $activeSchoolYear?->name ?? '—' }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Grade Levels</p>
            <p class="text-3xl font-medium text-zinc-800 dark:text-zinc-100">{{ $totalGradeLevels }}</p>
            <p class="text-xs text-zinc-400 mt-1">Elementary to Senior High</p>
        </div>

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Not Yet Enrolled</p>
            <p class="text-3xl font-medium text-yellow-500">{{ $totalStudents - $enrolledThisYear }}</p>
            <p class="text-xs text-zinc-400 mt-1">Students without enrollment</p>
        </div>
    </div>

    <div class="grid grid-cols-[1fr_340px] gap-4">

        {{-- Enrollment by Grade Level --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">
                Enrollment by Grade Level
            </p>

            @if ($enrollmentsByGradeLevel->isEmpty())
                <p class="text-sm text-zinc-400 text-center py-6">No enrollment data yet.</p>
            @else
                <div class="space-y-3">
                    @php $max = $enrollmentsByGradeLevel->max('enrollments_count') ?: 1; @endphp
                    @foreach ($enrollmentsByGradeLevel as $level)
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-zinc-500 w-16 shrink-0">{{ $level->name }}</span>
                            <div class="flex-1 bg-zinc-100 dark:bg-zinc-700 rounded-full h-2">
                                <div
                                    class="bg-blue-500 h-2 rounded-full transition-all"
                                    style="width: {{ ($level->enrollments_count / $max) * 100 }}%"
                                ></div>
                            </div>
                            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-300 w-6 text-right">
                                {{ $level->enrollments_count }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Enrollments --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider">Recent Enrollments</p>
                <a href="{{ route('admin.students.index') }}" class="text-xs text-blue-500 hover:text-blue-700">View all</a>
            </div>

            @if ($recentEnrollments->isEmpty())
                <p class="text-sm text-zinc-400 text-center py-6">No enrollments yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentEnrollments as $enrollment)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                    {{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}{{ strtoupper(substr($enrollment->student->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
                                </p>
                                <p class="text-xs text-zinc-400">
                                    {{ $enrollment->gradeLevel->name }} — {{ $enrollment->section->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>