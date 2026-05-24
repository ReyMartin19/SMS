<div>
    <div class="mb-6 print:hidden">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item icon="home" href="{{ route('admin.dashboard') }}" />
            <flux:breadcrumbs.item>Grades</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Report Card</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4 flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Report Card</flux:heading>
                <flux:subheading>View student report cards</flux:subheading>
            </div>
            @if($this->reportData)
                <flux:button icon="printer" variant="primary" onclick="window.print()">Print</flux:button>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-6 print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:select wire:model.live="schoolYearId" label="School Year">
                <option value="">Select...</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="studentId" label="Student">
                <option value="">Select...</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->last_name }}, {{ $student->first_name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    @if($this->reportData)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-8 print:border-none print:shadow-none print:p-0">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold uppercase tracking-wide">Report Card</h2>
                <p class="text-lg mt-2">{{ $this->reportData['schoolYear']->name }}</p>
                
                <div class="mt-6 flex flex-col md:flex-row justify-between text-left border-b border-zinc-200 dark:border-zinc-700 pb-4">
                    <div>
                        <p class="text-sm text-zinc-500">Student Name</p>
                        <p class="font-medium text-lg">{{ $this->reportData['student']->last_name }}, {{ $this->reportData['student']->first_name }} {{ $this->reportData['student']->middle_name }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 text-left md:text-right">
                        <p class="text-sm text-zinc-500">LRN</p>
                        <p class="font-medium text-lg">{{ $this->reportData['student']->lrn }}</p>
                    </div>
                </div>
            </div>

            <table class="w-full text-sm border-collapse border border-zinc-300 dark:border-zinc-600 print:border-black">
                <thead>
                    <tr class="bg-zinc-100 dark:bg-zinc-800 print:bg-white text-zinc-900 dark:text-zinc-100 print:text-black">
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-left font-semibold">Learning Areas</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-16">Q1</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-16">Q2</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-16">Q3</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-16">Q4</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-24">Final Grade</th>
                        <th class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-semibold w-24">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->reportData['subjects'] as $subject)
                        <tr>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2">{{ $subject['subject_name'] }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center">{{ $subject['q1'] ?? '' }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center">{{ $subject['q2'] ?? '' }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center">{{ $subject['q3'] ?? '' }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center">{{ $subject['q4'] ?? '' }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center font-bold">{{ $subject['final_grade'] ?? '' }}</td>
                            <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-2 text-center text-xs uppercase">{{ $subject['remarks'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-3 text-right font-bold uppercase tracking-wider">General Average</td>
                        <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-3 text-center font-bold text-lg">{{ $this->reportData['general_average'] ?? '' }}</td>
                        <td class="border border-zinc-300 dark:border-zinc-600 print:border-black px-4 py-3 text-center text-xs uppercase font-bold">
                            @if($this->reportData['general_average'] !== null)
                                {{ $this->reportData['general_average'] >= 75 ? 'Passed' : 'Failed' }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="mt-16 grid grid-cols-2 gap-8 text-center print:mt-24">
                <div>
                    <div class="border-b border-black w-3/4 mx-auto"></div>
                    <p class="mt-2 text-sm uppercase">Teacher's Signature</p>
                </div>
                <div>
                    <div class="border-b border-black w-3/4 mx-auto"></div>
                    <p class="mt-2 text-sm uppercase">Principal's Signature</p>
                </div>
            </div>
        </div>
    @endif
</div>
