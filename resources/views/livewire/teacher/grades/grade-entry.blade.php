<div>
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item icon="home" href="{{ route('teacher.dashboard') }}" />
            <flux:breadcrumbs.item>Grades</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Entry</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">Grade Entry</flux:heading>
            <flux:subheading>Manage your class grades</flux:subheading>
        </div>
    </div>

    <x-flash-message />

    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:select wire:model.live="schoolYearId" label="School Year">
                <option value="">Select...</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="assignmentId" label="Subject & Section">
                <option value="">Select...</option>
                @foreach($assignments as $assign)
                    <option value="{{ $assign->id }}">{{ $assign->subject->name }} - {{ $assign->section->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="quarter" label="Quarter">
                <option value="">Select...</option>
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3">Q3</option>
                <option value="4">Q4</option>
            </flux:select>
        </div>
    </div>

    @if(!empty($grades))
        <form wire:submit.prevent="saveGrades">
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm divide-y divide-zinc-100 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-32">Written (25%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-32">Perf. Task (50%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-32">Assessment (25%)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-zinc-400 uppercase tracking-wider">Qtr Grade</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-zinc-400 uppercase tracking-wider">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            @foreach($grades as $gradeId => $data)
                                <tr wire:key="grade-row-{{ $gradeId }}-{{ $this->quarter }}">
                                    <td class="px-6 py-3 whitespace-nowrap">{{ $data['student_name'] }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <flux:input type="number" step="0.01" wire:model="grades.{{ $gradeId }}.written_works_score" class="w-full" size="sm" />
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <flux:input type="number" step="0.01" wire:model="grades.{{ $gradeId }}.performance_task_score" class="w-full" size="sm" />
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <flux:input type="number" step="0.01" wire:model="grades.{{ $gradeId }}.quarterly_assessment_score" class="w-full" size="sm" />
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center font-medium {{ $data['quarter_grade'] >= 75 ? 'text-green-600' : ($data['quarter_grade'] ? 'text-red-500' : '') }}">
                                        {{ $data['quarter_grade'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        @if($data['remarks'] === 'Passed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Passed</span>
                                        @elseif($data['remarks'] === 'Failed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Failed</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ $data['remarks'] ?? 'Incomplete' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                    <i class="ti ti-check" wire:loading.remove wire:target="saveGrades"></i>
                    <flux:icon.loading wire:loading wire:target="saveGrades" class="w-4 h-4" />
                    Save All Grades
                </flux:button>
            </div>
        </form>
    @else
        @if($schoolYearId && $assignmentId && $quarter)
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-8 text-center text-zinc-500">
                No students enrolled in this section.
            </div>
        @endif
    @endif
</div>
