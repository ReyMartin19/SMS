<div>
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-xs text-zinc-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center hover:text-blue-600">
                    <i class="ti ti-smart-home mr-1"></i> Teacher
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">Dashboard</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Error States / Warnings --}}
    @if (!$teacher)
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i class="ti ti-alert-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-red-800 dark:text-red-400">Teacher Profile Missing</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    Your teacher profile is not set up yet. Please contact the administrator to create your teacher record and link it to your account.
                </p>
            </div>
        </div>
    @elseif (!$activeSchoolYear)
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                <i class="ti ti-calendar-off text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-amber-800 dark:text-amber-400">No Active School Year</h3>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                    There is currently no active academic school year set in the system. Dashboard stats and student lists will not be loaded. Please contact the administrator.
                </p>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                        <i class="ti ti-layout-dashboard text-xl"></i>
                    </div>
                    Welcome back, {{ $teacher->first_name }} {{ $teacher->last_name }}!
                </h1>
                <p class="text-sm text-zinc-500 mt-2 flex items-center gap-2">
                    <span>Academic Year Portal</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700">
                        <i class="ti ti-calendar-stats mr-1 text-blue-500"></i>
                        {{ $activeSchoolYear->name }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card 1: Assigned Subjects --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="ti ti-book-2 text-8xl text-blue-600"></i>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Assigned Subjects</p>
                    <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <i class="ti ti-book-2 text-lg"></i>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalSubjects'] }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                        <i class="ti ti-circle-check text-green-500"></i> Current school year
                    </p>
                </div>
            </div>

            {{-- Card 2: Assigned Sections --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="ti ti-layers-intersect text-8xl text-indigo-600"></i>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Assigned Sections</p>
                    <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <i class="ti ti-layers-intersect text-lg"></i>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalSections'] }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                        <i class="ti ti-chalkboard text-zinc-400"></i> Distinct cohorts
                    </p>
                </div>
            </div>

            {{-- Card 3: Total Students --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="ti ti-users text-8xl text-emerald-600"></i>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Students</p>
                    <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-users text-lg"></i>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalStudents'] }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                        <i class="ti ti-user-check text-emerald-500"></i> Enrolled across sections
                    </p>
                </div>
            </div>

            {{-- Card 4: Grades Entered --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="ti ti-award text-8xl text-amber-500"></i>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Grades Entered</p>
                    <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <i class="ti ti-award text-lg"></i>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalGradesEntered'] }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 flex items-center gap-1">
                        <i class="ti ti-bookmark text-amber-500"></i> Graded quarters
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Two-Column Content Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">

            {{-- Left Column: Tables --}}
            <div class="space-y-6">

                {{-- Assigned Subjects Card --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                            <i class="ti ti-book-open text-blue-500 text-lg"></i> Assigned Subjects
                        </h2>
                    </div>
                    <div class="p-0">
                        @if ($assignments->isEmpty())
                            <div class="flex flex-col items-center justify-center text-zinc-400 py-10">
                                <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="ti ti-book-off text-3xl opacity-50"></i>
                                </div>
                                <p class="text-sm font-medium">No assigned subjects</p>
                                <p class="text-xs mt-1 text-zinc-500 text-center">You have not been assigned any subjects for this academic year.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider border-collapse">
                                            <th class="px-6 py-4">Subject</th>
                                            <th class="px-6 py-4">Grade & Section</th>
                                            <th class="px-6 py-4 text-center">Students</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                        @foreach ($assignments as $assignment)
                                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $assignment->subject->name }}</div>
                                                    <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ $assignment->subject->code }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-zinc-800 dark:text-zinc-200">{{ $assignment->section->gradeLevel->name }}</span>
                                                    <span class="text-zinc-400 dark:text-zinc-500 text-xs block">Sec: {{ $assignment->section->name }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700">
                                                        {{ $assignment->student_count }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <a href="{{ route('teacher.grades.entry', ['assignmentId' => $assignment->id, 'schoolYearId' => $activeSchoolYear->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 border border-blue-100 dark:border-blue-900/20 transition-colors" wire:navigate>
                                                        <i class="ti ti-pencil-square mr-1"></i> Go to Grades
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recent Grade Entries Card --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                            <i class="ti ti-history text-indigo-500 text-lg"></i> Recent Grade Entries
                        </h2>
                    </div>
                    <div class="p-0">
                        @if ($recentGrades->isEmpty())
                            <div class="flex flex-col items-center justify-center text-zinc-400 py-10">
                                <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="ti ti-clipboard-off text-3xl opacity-50"></i>
                                </div>
                                <p class="text-sm font-medium">No recent grade entries</p>
                                <p class="text-xs mt-1 text-zinc-500 text-center">Grades you enter will appear here for quick review.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider border-collapse">
                                            <th class="px-6 py-4">Student</th>
                                            <th class="px-6 py-4">Subject & Section</th>
                                            <th class="px-6 py-4 text-center">Quarter</th>
                                            <th class="px-6 py-4 text-center">Grade</th>
                                            <th class="px-6 py-4 text-right">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                        @foreach ($recentGrades as $grade)
                                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-zinc-800 dark:text-zinc-200">
                                                        {{ $grade->student->last_name }}, {{ $grade->student->first_name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-zinc-800 dark:text-zinc-200">{{ $grade->subject->name }}</div>
                                                    <div class="text-xs text-zinc-400 dark:text-zinc-500">Sec: {{ $grade->section->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400 font-medium">
                                                    Q{{ $grade->quarter }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($grade->quarter_grade >= 75)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                                            {{ number_format($grade->quarter_grade, 1) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/20 text-red-500 dark:text-red-400 border border-red-100 dark:border-red-900/30">
                                                            {{ number_format($grade->quarter_grade, 1) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if ($grade->remarks === 'Passed')
                                                        <span class="text-xs font-medium text-green-600 dark:text-green-400">Passed</span>
                                                    @elseif ($grade->remarks === 'Failed')
                                                        <span class="text-xs font-medium text-red-500 dark:text-red-400">Failed</span>
                                                    @else
                                                        <span class="text-xs font-medium text-zinc-400 dark:text-zinc-500">Incomplete</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right Column: Announcements --}}
            <div>
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                            <i class="ti ti-megaphone text-orange-500 text-lg"></i> Announcements
                        </h2>
                    </div>

                    <div class="p-3 flex-1 space-y-3">
                        @if ($announcements->isEmpty())
                            <div class="flex flex-col items-center justify-center text-zinc-400 py-10">
                                <div class="w-12 h-12 mb-3 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="ti ti-bell-off text-2xl opacity-50"></i>
                                </div>
                                <p class="text-xs font-medium text-zinc-500">No active announcements</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($announcements as $announcement)
                                    @php
                                        $isExpanded = in_array($announcement->id, $expandedAnnouncements);
                                    @endphp
                                    <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 rounded-xl p-4 transition-all duration-200 relative overflow-hidden group hover:border-zinc-200 dark:hover:border-zinc-700 {{ $announcement->is_pinned ? 'border-l-4 border-l-amber-500 dark:border-l-amber-500' : '' }}">
                                        
                                        {{-- Header details --}}
                                        <div class="flex justify-between items-start gap-2 mb-1.5">
                                            <h3 class="text-xs font-semibold text-zinc-850 dark:text-zinc-200 line-clamp-2">
                                                @if ($announcement->is_pinned)
                                                    <i class="ti ti-pin text-amber-500 mr-0.5" title="Pinned Announcement"></i>
                                                @endif
                                                {{ $announcement->title }}
                                            </h3>
                                            
                                            <button 
                                                wire:click="toggleAnnouncement({{ $announcement->id }})" 
                                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 shrink-0 p-0.5 rounded hover:bg-zinc-200/50 dark:hover:bg-zinc-700/50 transition-colors cursor-pointer"
                                                aria-label="Toggle announcement details"
                                            >
                                                <i class="ti ti-chevron-{{ $isExpanded ? 'up' : 'down' }} text-sm"></i>
                                            </button>
                                        </div>

                                        {{-- Badges and Metadata --}}
                                        <div class="flex items-center gap-2 mb-3 text-[10px] text-zinc-400 dark:text-zinc-500">
                                            <span>{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}</span>
                                            
                                            @if ($announcement->audience === 'all')
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-950/40">
                                                    All
                                                </span>
                                            @else
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-950/40">
                                                    Teachers
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Body text --}}
                                        <div class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed font-normal">
                                            @if ($isExpanded)
                                                <div class="whitespace-pre-line prose prose-sm dark:prose-invert">
                                                    {{ $announcement->body }}
                                                </div>
                                            @else
                                                <p class="line-clamp-2">
                                                    {{ strip_tags($announcement->body) }}
                                                </p>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>