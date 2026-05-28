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
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">My Grades</span>
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
    @elseif (empty($schoolYears))
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-6 shadow-sm mb-8 flex items-start gap-4">
            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                <i class="ti ti-user-exclamation text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-amber-800 dark:text-amber-400">No Enrollment Records</h3>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                    You have no enrollment records. Please contact the administrator.
                </p>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-medium text-zinc-900 dark:text-white flex items-center gap-2">
                    <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                        <i class="ti ti-academic-cap text-xl"></i>
                    </div>
                    My Grades
                </h1>
                <p class="text-sm text-zinc-500 mt-1">
                    View your academic grades across all school years.
                </p>
            </div>
        </div>

        {{-- School Year Tabs --}}
        @if (count($schoolYears) > 0)
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                @foreach ($schoolYears as $year)
                    <button
                        wire:click="selectSchoolYear({{ $year->id }})"
                        class="px-4 py-2 text-sm rounded-lg whitespace-nowrap border transition cursor-pointer
                            {{ $selectedSchoolYearId === $year->id
                                ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                : 'border-zinc-300 dark:border-zinc-700 text-zinc-650 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                    >
                        {{ $year->name }}
                        @if ($year->is_active)
                            <span class="ml-1 text-xs opacity-75 font-semibold">(Current)</span>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Enrollment Info for Selected Year --}}
        @if ($enrollment)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 mb-4 flex flex-wrap items-center gap-x-6 gap-y-2">
                <div>
                    <p class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">
                        School Year
                    </p>
                    <p class="font-semibold text-blue-700 dark:text-blue-300 text-sm">
                        {{ $enrollment->schoolYear->name }}
                    </p>
                </div>
                <div class="hidden sm:block w-px h-8 bg-blue-200 dark:bg-blue-800"></div>
                <div>
                    <p class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">
                        Grade Level
                    </p>
                    <p class="font-semibold text-blue-700 dark:text-blue-300 text-sm">
                        {{ $enrollment->gradeLevel->name }}
                    </p>
                </div>
                <div class="hidden sm:block w-px h-8 bg-blue-200 dark:bg-blue-800"></div>
                <div>
                    <p class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">
                        Section
                    </p>
                    <p class="font-semibold text-blue-700 dark:text-blue-300 text-sm">
                        {{ $enrollment->section->name }}
                    </p>
                </div>
                <div class="hidden sm:block w-px h-8 bg-blue-200 dark:bg-blue-800"></div>
                <div>
                    <p class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">
                        Status
                    </p>
                    @php
                        $eColors = [
                            'enrolled'    => 'text-green-600 dark:text-green-455',
                            'graduated'   => 'text-blue-600 dark:text-blue-455',
                            'dropped'     => 'text-red-600 dark:text-red-455',
                            'transferred' => 'text-yellow-605 dark:text-yellow-455',
                        ];
                    @endphp
                    <p class="font-semibold text-sm {{ $eColors[strtolower($enrollment->status)] ?? '' }}">
                        {{ ucfirst($enrollment->status) }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Grades Table --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-150 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Subject</th>
                            <th class="px-4 py-3.5 text-center">Q1</th>
                            <th class="px-4 py-3.5 text-center">Q2</th>
                            <th class="px-4 py-3.5 text-center">Q3</th>
                            <th class="px-4 py-3.5 text-center">Q4</th>
                            <th class="px-4 py-3.5 text-center">Final</th>
                            <th class="px-4 py-3.5 text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($grades as $grade)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-zinc-800 dark:text-zinc-200">
                                        {{ $grade['subject_name'] }}
                                    </p>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500">
                                        {{ $grade['subject_code'] }}
                                    </p>
                                </td>
                                @foreach (['q1', 'q2', 'q3', 'q4'] as $quarter)
                                    <td class="px-4 py-4 text-center">
                                        @if ($grade[$quarter] !== null)
                                            <span class="{{ $grade[$quarter] >= 75 
                                                ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }} font-semibold">
                                                {{ number_format($grade[$quarter], 2) }}
                                            </span>
                                        @else
                                            <span class="text-zinc-300 dark:text-zinc-700">—</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-4 text-center">
                                    @if ($grade['final_grade'] !== null)
                                        <span class="font-bold {{ $grade['final_grade'] >= 75 
                                            ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                                            {{ number_format($grade['final_grade'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-zinc-300 dark:text-zinc-700">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $remarkColors = [
                                            'Passed'     => 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400 border border-green-100 dark:border-green-900/30',
                                            'Failed'     => 'bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400 border border-red-100 dark:border-red-900/30',
                                            'Incomplete' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/20 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-900/30',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $remarkColors[$grade['remarks']] ?? '' }}">
                                        {{ $grade['remarks'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-zinc-400 dark:text-zinc-500">
                                    <i class="ti ti-clipboard-x text-zinc-300 dark:text-zinc-700" 
                                        style="font-size:36px; display:block; margin:0 auto 12px">
                                    </i>
                                    No grades recorded for this school year yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Overall Average Footer --}}
                    @if (!empty($grades))
                        @php
                            $validFinals = collect($grades)
                                ->filter(fn($g) => $g['final_grade'] !== null)
                                ->pluck('final_grade');
                            $overallAverage = $validFinals->count() > 0
                                ? round($validFinals->avg(), 2)
                                : null;
                        @endphp
                        @if ($overallAverage !== null)
                            <tfoot>
                                <tr class="border-t-2 border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-850/50">
                                    <td class="px-5 py-4 font-semibold text-zinc-600 dark:text-zinc-400 text-sm" colspan="5">
                                        Overall Average
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="font-bold text-base 
                                            {{ $overallAverage >= 75 
                                                ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                            {{ number_format($overallAverage, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border
                                            {{ $overallAverage >= 75
                                                ? 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400 border-green-100 dark:border-green-900/30'
                                                : 'bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-450 border-red-100 dark:border-red-900/30' }}">
                                            {{ $overallAverage >= 75 ? 'Promoted' : 'Retained' }}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    @endif
                </table>
            </div>
        </div>
    @endif
</div>
