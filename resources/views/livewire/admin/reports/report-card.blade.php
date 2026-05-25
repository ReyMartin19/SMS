<div class="space-y-6">
    <!-- Top Control Bar (Hidden on Print) -->
    <div class="print:hidden space-y-4">
        <!-- Breadcrumbs -->
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
            <flux:breadcrumbs.item href="{{ route('admin.reports.index') }}">Reports</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Report Card Preview</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <!-- Main Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200 dark:border-zinc-700 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <a href="{{ route('admin.reports.index') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                    <span>Report Card Preview</span>
                </h1>
                @if($student)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                        Viewing academic grades for <strong class="text-zinc-700 dark:text-zinc-200">{{ $student->last_name }}, {{ $student->first_name }}</strong> (LRN: {{ $student->lrn }})
                    </p>
                @endif
            </div>

            @if($student && $enrollment)
                <div class="flex items-center gap-2.5">
                    <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white font-medium text-sm transition-colors shadow-sm cursor-pointer">
                        <i class="ti ti-printer text-base"></i> Print Card
                    </button>
                    <a href="{{ route('admin.reports.pdf.report-card', ['student' => $student->id, 'school_year_id' => $schoolYearId]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow-sm">
                        <i class="ti ti-file-type-pdf text-base"></i> Download PDF
                    </a>
                </div>
            @endif
        </div>

        <!-- School Year Filter Bar -->
        @if($student)
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="w-full sm:w-72">
                    <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5">Selected Academic School Year</label>
                    <select wire:model.live="schoolYearId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}">{{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if(!$enrollment)
                    <div class="flex-1 flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/30 p-3 rounded-lg">
                        <i class="ti ti-alert-circle text-lg"></i>
                        <span>This student is not enrolled in the selected school year. Please select another year.</span>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 text-center text-zinc-500 dark:text-zinc-400">
                <i class="ti ti-user-x text-4xl block mb-2"></i>
                <p class="font-medium text-sm">No student selected.</p>
                <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 block font-semibold">&larr; Return to Report Center</a>
            </div>
        @endif
    </div>

    <!-- Printable Report Card Preview Section -->
    @if($student && $enrollment)
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 md:p-10 shadow-sm max-w-4xl mx-auto">
            <!-- Report Card border & container -->
            <div class="border-2 border-zinc-950 dark:border-zinc-100 p-6 md:p-8 space-y-8 bg-white dark:bg-zinc-950 text-zinc-950 dark:text-zinc-50">
                
                <!-- School Info Header -->
                <div class="text-center border-b double border-zinc-950 dark:border-zinc-100 pb-5 space-y-1">
                    <h1 class="text-xl md:text-2xl font-black uppercase tracking-wider">School Management System</h1>
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-600 dark:text-zinc-400">Student Progress Report Card</h2>
                    <h3 class="text-xxs tracking-widest text-zinc-500 dark:text-zinc-500">OFFICIAL ACADEMIC TRANSCRIPT OF RECORD</h3>
                </div>

                <!-- Profile Info Box -->
                <div>
                    <h4 class="text-xs font-black uppercase border-b border-zinc-950 dark:border-zinc-100 pb-1 mb-3">Student Profile</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-xs md:text-sm">
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">Student Name:</span>
                            <span class="font-black">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->suffix ?? '' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">LRN (12-Digit):</span>
                            <span class="font-black tracking-wider">{{ $student->lrn }}</span>
                        </div>
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">Grade & Section:</span>
                            <span class="font-black">{{ $enrollment->gradeLevel->name ?? 'N/A' }} - {{ $enrollment->section->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">School Year:</span>
                            <span class="font-black">{{ $enrollment->schoolYear->name }}</span>
                        </div>
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">Gender:</span>
                            <span class="font-black capitalize">{{ $student->gender }}</span>
                        </div>
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-800 py-1">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400">Date of Birth:</span>
                            <span class="font-black">{{ $student->birthdate ? $student->birthdate->format('F d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Grading Table Grid -->
                <div>
                    <h4 class="text-xs font-black uppercase border-b border-zinc-950 dark:border-zinc-100 pb-1 mb-3">Report on Learning Progress & Achievement</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-xs md:text-sm text-center">
                            <thead>
                                <tr class="bg-zinc-100 dark:bg-zinc-900 border border-zinc-950 dark:border-zinc-100">
                                    <th class="text-left py-3 px-4 uppercase font-black tracking-wider border-r border-zinc-950 dark:border-zinc-100" style="width: 35%;">Learning Areas</th>
                                    <th class="py-3 px-2 border-r border-zinc-950 dark:border-zinc-100" style="width: 10%;">Q1</th>
                                    <th class="py-3 px-2 border-r border-zinc-950 dark:border-zinc-100" style="width: 10%;">Q2</th>
                                    <th class="py-3 px-2 border-r border-zinc-950 dark:border-zinc-100" style="width: 10%;">Q3</th>
                                    <th class="py-3 px-2 border-r border-zinc-950 dark:border-zinc-100" style="width: 10%;">Q4</th>
                                    <th class="py-3 px-2 border-r border-zinc-950 dark:border-zinc-100" style="width: 13%;">Final Grade</th>
                                    <th class="py-3 px-2" style="width: 12%;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gradesData as $data)
                                    <tr class="border-b border-zinc-950 dark:border-zinc-100 border-l border-r border-zinc-950 dark:border-zinc-100">
                                        <td class="text-left py-3 px-4 font-bold border-r border-zinc-950 dark:border-zinc-100">{{ $data['subject_name'] }}</td>
                                        <td class="border-r border-zinc-950 dark:border-zinc-100">{{ $data['q1'] !== null ? number_format($data['q1'], 0) : '-' }}</td>
                                        <td class="border-r border-zinc-950 dark:border-zinc-100">{{ $data['q2'] !== null ? number_format($data['q2'], 0) : '-' }}</td>
                                        <td class="border-r border-zinc-950 dark:border-zinc-100">{{ $data['q3'] !== null ? number_format($data['q3'], 0) : '-' }}</td>
                                        <td class="border-r border-zinc-950 dark:border-zinc-100">{{ $data['q4'] !== null ? number_format($data['q4'], 0) : '-' }}</td>
                                        <td class="font-black border-r border-zinc-950 dark:border-zinc-100">{{ $data['final_grade'] !== null ? number_format($data['final_grade'], 1) : '-' }}</td>
                                        <td class="font-black py-2 px-1">
                                            @if($data['remarks'] === 'Passed')
                                                <span class="text-green-600 dark:text-green-400">PASSED</span>
                                            @elseif($data['remarks'] === 'Failed')
                                                <span class="text-red-600 dark:text-red-400">FAILED</span>
                                            @else
                                                <span class="text-zinc-400 dark:text-zinc-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-b border-zinc-950 dark:border-zinc-100 border-l border-r border-zinc-950 dark:border-zinc-100">
                                        <td colspan="7" class="py-6 text-zinc-500 dark:text-zinc-400 italic">No academic subjects assigned for this grade level.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Average & Remarks Panel -->
                <div class="border border-zinc-950 dark:border-zinc-100 bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 text-sm">
                    <div class="flex items-center justify-between p-4 border-b md:border-b-0 md:border-r border-zinc-950 dark:border-zinc-100">
                        <span class="font-bold text-zinc-700 dark:text-zinc-300">GENERAL AVERAGE:</span>
                        <span class="text-lg font-black tracking-wider">{{ $overallAverage !== null ? number_format($overallAverage, 2) : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4">
                        <span class="font-bold text-zinc-700 dark:text-zinc-300">PROMOTION STATUS:</span>
                        @if($promotionStatus === 'Promoted')
                            <span class="text-lg font-black text-green-600 dark:text-green-400 tracking-wider">PROMOTED</span>
                        @elseif($promotionStatus === 'Retained')
                            <span class="text-lg font-black text-red-600 dark:text-red-400 tracking-wider">RETAINED</span>
                        @else
                            <span class="text-lg font-black text-zinc-500 dark:text-zinc-400 tracking-wider">INCOMPLETE</span>
                        @endif
                    </div>
                </div>

                <!-- Signatures -->
                <div class="grid grid-cols-2 gap-12 pt-8 text-center text-xs md:text-sm">
                    <div class="space-y-1">
                        <div class="border-t border-zinc-950 dark:border-zinc-100 pt-2 w-3/4 mx-auto font-black uppercase">
                            {{ $enrollment->section->teacher_name ?? 'Class Adviser' }}
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 text-xxs tracking-wider">CLASS ADVISER</div>
                    </div>
                    <div class="space-y-1">
                        <div class="border-t border-zinc-950 dark:border-zinc-100 pt-2 w-3/4 mx-auto font-black uppercase">
                            School Principal
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 text-xxs tracking-wider">PRINCIPAL / SCHOOL HEAD</div>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
