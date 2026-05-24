<div>
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-xs text-zinc-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center hover:text-blue-600">
                    <i class="ti ti-smart-home mr-1"></i> Admin
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">Overview</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-layout-dashboard text-xl"></i>
                </div>
                Dashboard Overview
            </h1>
            <p class="text-sm text-zinc-500 mt-2 flex items-center gap-2">
                <span>Academic Year Performance</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700">
                    <i class="ti ti-calendar-stats mr-1 text-blue-500"></i>
                    {{ $activeSchoolYear?->name ?? 'No active school year' }}
                </span>
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Total Students --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ti ti-users text-8xl text-blue-600"></i>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Students</p>
                <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <i class="ti ti-users text-lg"></i>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalStudents }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                    <i class="ti ti-database text-zinc-400"></i> All registered records
                </p>
            </div>
        </div>

        {{-- Card 2: Enrolled This Year --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-md relative overflow-hidden group hover:shadow-lg transition-shadow text-white">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="ti ti-user-check text-8xl text-white"></i>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Enrolled This Year</p>
                <div class="p-2 rounded-lg bg-white/20 text-white backdrop-blur-sm">
                    <i class="ti ti-chart-arcs text-lg"></i>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ $enrolledThisYear }}</p>
                <p class="text-xs text-blue-100 mt-2 flex items-center gap-1">
                    <i class="ti ti-calendar-event opacity-80"></i> {{ $activeSchoolYear?->name ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Card 3: Grade Levels --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ti ti-books text-8xl text-emerald-600"></i>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Grade Levels</p>
                <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-layers-linked text-lg"></i>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalGradeLevels }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                    <i class="ti ti-school text-zinc-400"></i> Elementary to Senior High
                </p>
            </div>
        </div>

        {{-- Card 4: Not Yet Enrolled --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ti ti-user-exclamation text-8xl text-amber-500"></i>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Not Yet Enrolled</p>
                <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                    <i class="ti ti-user-pause text-lg"></i>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalStudents - $enrolledThisYear }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                    <i class="ti ti-alert-circle text-amber-500"></i> Requires action
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6">

        {{-- Enrollment by Grade Level --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                    <i class="ti ti-chart-bar text-blue-500 text-lg"></i> Enrollment by Grade Level
                </h2>
            </div>

            <div class="p-6 flex-1">
                @if ($enrollmentsByGradeLevel->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-zinc-400 py-10">
                        <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="ti ti-chart-pie text-3xl opacity-50"></i>
                        </div>
                        <p class="text-sm font-medium">No enrollment data yet</p>
                        <p class="text-xs mt-1 text-center max-w-xs">Once students are enrolled, their distribution will appear here.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @php $max = $enrollmentsByGradeLevel->max('enrollments_count') ?: 1; @endphp
                        @foreach ($enrollmentsByGradeLevel as $level)
                            <div class="group">
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ $level->name }}</span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded-md border border-zinc-200 dark:border-zinc-700">
                                        {{ $level->enrollments_count }}
                                    </span>
                                </div>
                                <div class="w-full bg-zinc-100 dark:bg-zinc-800/50 rounded-full h-2 shadow-inner overflow-hidden border border-zinc-200/50 dark:border-zinc-700/50">
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full relative group-hover:opacity-90 transition-opacity"
                                        style="width: {{ ($level->enrollments_count / $max) * 100 }}%"
                                    >
                                        <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Enrollments --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                    <i class="ti ti-history text-indigo-500 text-lg"></i> Recent Enrollments
                </h2>
                <a href="{{ route('admin.students.index') ?? '#' }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1 group">
                    View all <i class="ti ti-arrow-right transform group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>

            <div class="p-2 flex-1">
                @if ($recentEnrollments->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-zinc-400 py-10">
                        <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="ti ti-users-x text-3xl opacity-50"></i>
                        </div>
                        <p class="text-sm font-medium">No recent activity</p>
                    </div>
                @else
                    <ul class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                        @foreach ($recentEnrollments as $enrollment)
                            <li>
                                <div class="px-4 py-3.5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors flex items-center gap-3 rounded-xl m-1 group cursor-default">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/40 dark:to-indigo-900/40 border border-blue-200 dark:border-blue-800/50 flex items-center justify-center shrink-0 shadow-inner group-hover:shadow-md transition-shadow">
                                        <span class="text-sm font-bold text-blue-700 dark:text-blue-300">
                                            {{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}{{ strtoupper(substr($enrollment->student->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 truncate">
                                            {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[11px] font-medium px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 rounded border border-zinc-200 dark:border-zinc-700 truncate max-w-[120px]">
                                                {{ $enrollment->gradeLevel->name }}
                                            </span>
                                            <span class="text-[11px] text-zinc-500 truncate">
                                                Sec: {{ $enrollment->section->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            @if (!$recentEnrollments->isEmpty())
            <div class="p-3 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/30 dark:bg-zinc-800/30">
                <a href="{{ route('admin.students.index') ?? '#' }}" class="w-full py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs font-medium text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    Open Student Directory
                </a>
            </div>
            @endif
        </div>

    </div>
</div>