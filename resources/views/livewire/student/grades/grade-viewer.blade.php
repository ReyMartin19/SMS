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
                    <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600 ml-1 md:ml-2">Dashboard</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">Academic Grades</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Fallbacks --}}
    @if (!$student)
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl">
                <i class="ti ti-alert-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-red-800 dark:text-red-400">Student Profile Missing</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    Your student profile is not set up yet. Please contact the administrator.
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
                    No active academic school year set. Grades cannot be loaded.
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
                    You are not enrolled for the active school year.
                </p>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-xl font-medium text-zinc-900 dark:text-white flex items-center gap-2">
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-academic-cap text-xl"></i>
                </div>
                Report Card & Academic Grades
            </h1>
            <p class="text-sm text-zinc-500 mt-1">
                View your complete academic performance, term marks, and final results for school year <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $activeSchoolYear->name }}</span>.
            </p>
        </div>

        {{-- Main Container Card --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden mb-8">
            
            {{-- Tabs Header --}}
            <div class="border-b border-zinc-150 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 px-6 py-4 flex items-center justify-between overflow-x-auto gap-4">
                <div class="flex space-x-1 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-lg border border-zinc-200/50 dark:border-zinc-700/50 shrink-0">
                    @foreach(['Q1', 'Q2', 'Q3', 'Q4', 'Final'] as $tab)
                        <button 
                            wire:click="setActiveTab('{{ $tab }}')" 
                            class="px-4 py-1.5 text-xs font-semibold rounded-md transition-colors cursor-pointer {{ $activeTab === $tab ? 'bg-blue-600 text-white shadow-sm' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                        >
                            {{ $tab === 'Final' ? 'Final Grades' : $tab }}
                        </button>
                    @endforeach
                </div>

                <div class="text-xs text-zinc-400 dark:text-zinc-500 flex items-center gap-2">
                    <span>Enrolled Section: <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $enrollment->section->name }}</span></span>
                    <span>•</span>
                    <span>Grade Level: <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $enrollment->gradeLevel->name }}</span></span>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="p-0">
                @if ($subjects->isEmpty())
                    <div class="flex flex-col items-center justify-center text-zinc-400 py-16">
                        <div class="w-16 h-16 mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="ti ti-book-off text-3xl opacity-50"></i>
                        </div>
                        <p class="text-sm font-medium">No subjects found</p>
                    </div>
                @else
                    @if ($activeTab !== 'Final')
                        {{-- Term Tab View (Q1 - Q4) --}}
                        @php
                            $qNum = intval(substr($activeTab, 1));
                        @endphp
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                                        <th class="px-6 py-4">Subject</th>
                                        <th class="px-6 py-4 text-center">Written Works (25%)</th>
                                        <th class="px-6 py-4 text-center">Performance Tasks (50%)</th>
                                        <th class="px-6 py-4 text-center">Quarterly Assessment (25%)</th>
                                        <th class="px-6 py-4 text-center">Quarter Grade</th>
                                        <th class="px-6 py-4 text-right">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @php $hasGrades = false; @endphp
                                    @foreach ($subjects as $subject)
                                        @php
                                            $subjectGrades = $grades->get($subject->id) ?? collect();
                                            $grade = $subjectGrades->firstWhere('quarter', $qNum);
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
                            <div class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400 border-t border-zinc-100 dark:border-zinc-800">
                                <i class="ti ti-info-circle mr-1 text-blue-500"></i> No grades recorded yet for this quarter.
                            </div>
                        @endif
                    @else
                        {{-- Final Grades Tab View --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                                        <th class="px-6 py-4">Subject</th>
                                        <th class="px-6 py-4 text-center">Q1</th>
                                        <th class="px-6 py-4 text-center">Q2</th>
                                        <th class="px-6 py-4 text-center">Q3</th>
                                        <th class="px-6 py-4 text-center">Q4</th>
                                        <th class="px-6 py-4 text-center font-bold text-zinc-750 dark:text-zinc-300">Final Grade</th>
                                        <th class="px-6 py-4 text-right">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ($subjects as $subject)
                                        @php
                                            $subjectGrades = $grades->get($subject->id) ?? collect();
                                            $q1 = $subjectGrades->firstWhere('quarter', 1)?->quarter_grade;
                                            $q2 = $subjectGrades->firstWhere('quarter', 2)?->quarter_grade;
                                            $q3 = $subjectGrades->firstWhere('quarter', 3)?->quarter_grade;
                                            $q4 = $subjectGrades->firstWhere('quarter', 4)?->quarter_grade;
                                            $finalVal = $finalGrades[$subject->id] ?? null;
                                        @endphp
                                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $subject->name }}</div>
                                                <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ $subject->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">
                                                {{ !is_null($q1) ? number_format($q1, 1) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">
                                                {{ !is_null($q2) ? number_format($q2, 1) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">
                                                {{ !is_null($q3) ? number_format($q3, 1) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">
                                                {{ !is_null($q4) ? number_format($q4, 1) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if (!is_null($finalVal))
                                                    @if ($finalVal >= 75)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50">
                                                            {{ number_format($finalVal, 1) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                                                            {{ number_format($finalVal, 1) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-zinc-400 dark:text-zinc-500 font-semibold">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @if (!is_null($finalVal))
                                                    @if ($finalVal >= 75)
                                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-green-600 bg-green-50 dark:bg-green-900/10 dark:text-green-400 rounded border border-green-100 dark:border-green-900/20">Passed</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-red-500 bg-red-50 dark:bg-red-900/10 dark:text-red-450 rounded border border-red-100 dark:border-red-900/20">Failed</span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-zinc-450 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-450 rounded border border-zinc-200 dark:border-zinc-700">Incomplete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Overall Average Bottom Bar --}}
                        @if (!is_null($overallAverage))
                            <div class="px-6 py-5 bg-zinc-50 dark:bg-zinc-800/30 border-t border-zinc-150 dark:border-zinc-800 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2 text-zinc-550 dark:text-zinc-450 text-xs">
                                    <i class="ti ti-chart-bar text-indigo-500 text-base"></i>
                                    <span>Average of all calculated marks for the school year</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-550 uppercase tracking-wider">Overall Academic Average:</span>
                                    @if ($overallAverage >= 75)
                                        <span class="text-lg font-black text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900/30 px-3 py-1 rounded-xl shadow-inner">
                                            {{ number_format($overallAverage, 2) }}
                                        </span>
                                    @else
                                        <span class="text-lg font-black text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/30 px-3 py-1 rounded-xl shadow-inner">
                                            {{ number_format($overallAverage, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>

        </div>
    @endif
</div>
