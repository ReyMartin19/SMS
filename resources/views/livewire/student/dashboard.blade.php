<div>
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-xs text-zinc-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center hover:text-blue-600">
                    <i class="ti ti-smart-home mr-1"></i> Student
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

    {{-- Fallback States --}}
    @if (!$student)
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i class="ti ti-alert-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-red-800 dark:text-red-400">Student Profile Missing</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    Your student profile is not set up yet. Please contact the administrator to resolve this and link your account.
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
                    There is currently no active academic school year set in the system. Dashboard stats will not be loaded. Please contact the administrator.
                </p>
            </div>
        </div>
    @elseif (!$enrollment)
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                <i class="ti ti-user-exclamation text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-amber-800 dark:text-amber-400">Not Enrolled</h3>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                    You are not enrolled for the current school year ({{ $activeSchoolYear->name }}). Please contact the academic office.
                </p>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-xl font-medium text-zinc-900 dark:text-white flex items-center gap-2">
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-layout-dashboard text-xl"></i>
                </div>
                Student Portal Dashboard
            </h1>
            <p class="text-xs text-zinc-500 mt-1">
                Access your grades, enrollment records, and announcements for the current academic year.
            </p>
        </div>

        {{-- Main Two-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">

            {{-- Left Column: Enrollment Details & Grades --}}
            <div class="space-y-6">

                {{-- Enrollment Status Card --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="ti ti-school text-8xl text-blue-600"></i>
                    </div>
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider block">Enrollment Status</span>
                            <span class="text-lg font-bold text-zinc-900 dark:text-white mt-1 block">Active Enrollment</span>
                        </div>
                        @php
                            $statusColors = [
                                'enrolled' => 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400 border border-green-100 dark:border-green-900/30',
                                'dropped' => 'bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400 border border-red-100 dark:border-red-900/30',
                                'transferred' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30',
                                'graduated' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30',
                            ];
                            $statusColor = $statusColors[strtolower($enrollment->status)] ?? 'bg-zinc-50 text-zinc-700 dark:bg-zinc-950/20 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-t border-zinc-100 dark:border-zinc-800/50 pt-4">
                        <div>
                            <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider block">Grade Level</span>
                            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-0.5 block">{{ $enrollment->gradeLevel->name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider block">Section</span>
                            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-0.5 block">{{ $enrollment->section->name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider block">School Year</span>
                            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-0.5 block">{{ $activeSchoolYear->name }}</span>
                        </div>
                    </div>
                </div>

                {{-- Grades Table Card --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                            <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                                <i class="ti ti-academic-cap text-blue-500 text-lg"></i> Grades Summary
                            </h2>
                            <a href="{{ route('student.grades.index') }}" class="text-xs text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                View all years
                            </a>
                        </div>
                        
                        {{-- Quarter Switcher --}}
                        <div class="flex bg-zinc-100 dark:bg-zinc-850 p-1 rounded-lg border border-zinc-200/50 dark:border-zinc-700/50 shrink-0">
                            @foreach([1, 2, 3, 4] as $q)
                                <button 
                                    wire:click="setQuarter({{ $q }})" 
                                    class="px-3 py-1 text-xs font-semibold rounded-md transition-colors cursor-pointer {{ $quarter === $q ? 'bg-blue-600 text-white shadow-sm' : 'text-zinc-655 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-250' }}"
                                >
                                    Q{{ $q }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-0">
                        @if ($subjects->isEmpty())
                            <div class="flex flex-col items-center justify-center text-zinc-400 py-12">
                                <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800/55 flex items-center justify-center">
                                    <i class="ti ti-book-off text-3xl opacity-50"></i>
                                </div>
                                <p class="text-sm font-medium">No subjects found</p>
                                <p class="text-xs mt-1 text-zinc-500">There are no subjects assigned to your section for this school year.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                                            <th class="px-6 py-4">Subject</th>
                                            <th class="px-6 py-4 text-center">Written Works</th>
                                            <th class="px-6 py-4 text-center">Performance Tasks</th>
                                            <th class="px-6 py-4 text-center">Quarter Assessment</th>
                                            <th class="px-6 py-4 text-center">Quarter Grade</th>
                                            <th class="px-6 py-4 text-right">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                        @php $hasGrades = false; @endphp
                                        @foreach ($subjects as $subject)
                                            @php
                                                $grade = $grades->get($subject->id);
                                                if ($grade && !is_null($grade->quarter_grade)) {
                                                    $hasGrades = true;
                                                }
                                            @endphp
                                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $subject->name }}</div>
                                                    <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ $subject->code }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                                    {{ $grade && !is_null($grade->written_works_score) ? number_format($grade->written_works_score, 1) : '—' }}
                                                </td>
                                                <td class="px-6 py-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                                    {{ $grade && !is_null($grade->performance_task_score) ? number_format($grade->performance_task_score, 1) : '—' }}
                                                </td>
                                                <td class="px-6 py-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                                    {{ $grade && !is_null($grade->quarterly_assessment_score) ? number_format($grade->quarterly_assessment_score, 1) : '—' }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($grade && !is_null($grade->quarter_grade))
                                                        @if ($grade->quarter_grade >= 75)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                                                {{ number_format($grade->quarter_grade, 1) }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/20 text-red-500 dark:text-red-400 border border-red-100 dark:border-red-900/30">
                                                                {{ number_format($grade->quarter_grade, 1) }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-zinc-400 dark:text-zinc-500">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if ($grade && !is_null($grade->quarter_grade))
                                                        @if ($grade->remarks === 'Passed')
                                                            <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-green-600 bg-green-50 dark:bg-green-900/10 dark:text-green-400 rounded border border-green-100 dark:border-green-900/20">Passed</span>
                                                        @elseif ($grade->remarks === 'Failed')
                                                            <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-red-500 bg-red-50 dark:bg-red-900/10 dark:text-red-450 rounded border border-red-100 dark:border-red-900/20">Failed</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-amber-600 bg-amber-50 dark:bg-amber-900/10 dark:text-amber-400 rounded border border-amber-100 dark:border-amber-900/20">Incomplete</span>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-zinc-450 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-450 rounded border border-zinc-200 dark:border-zinc-700">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if (!$hasGrades)
                                <div class="px-6 py-8 text-center text-zinc-550 dark:text-zinc-450 border-t border-zinc-100 dark:border-zinc-800">
                                    <i class="ti ti-info-circle mr-1 text-blue-500"></i> No grades recorded yet for this quarter.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right Column: Announcements Feed --}}
            <div>
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                            <i class="ti ti-megaphone text-orange-500 text-lg"></i> Announcements
                        </h2>
                    </div>

                    <div class="p-3 space-y-3">
                        @if ($announcements->isEmpty())
                            <div class="flex flex-col items-center justify-center text-zinc-400 py-10">
                                <div class="w-12 h-12 mb-3 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                                    <i class="ti ti-bell-off text-2xl opacity-50"></i>
                                </div>
                                <p class="text-xs font-medium text-zinc-550">No active announcements</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($announcements as $announcement)
                                    @php
                                        $isExpanded = in_array($announcement->id, $expandedAnnouncements);
                                    @endphp
                                    <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 rounded-xl p-4 transition-all duration-200 relative overflow-hidden group hover:border-zinc-200 dark:hover:border-zinc-700 {{ $announcement->is_pinned ? 'border-l-4 border-l-amber-400 dark:border-l-amber-400' : '' }}">
                                        
                                        {{-- Header details --}}
                                        <div class="flex justify-between items-start gap-2 mb-1.5">
                                            <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-200 line-clamp-2">
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
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-950/40">
                                                    Student
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Body text --}}
                                        <div class="text-xs text-zinc-650 dark:text-zinc-400 leading-relaxed font-normal">
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