<div>
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item icon="home" href="{{ route('admin.dashboard') }}" />
            <flux:breadcrumbs.item>Grades</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Overview</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4 flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Grade Overview</flux:heading>
                <flux:subheading>Filter and override student grades</flux:subheading>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/20 text-green-700 rounded-xl p-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <flux:select wire:model.live="schoolYearId" label="School Year">
                <option value="">Select...</option>
                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="gradeLevelId" label="Grade Level">
                <option value="">Select...</option>
                @foreach($gradeLevels as $gl)
                    <option value="{{ $gl->id }}">{{ $gl->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="sectionId" label="Section">
                <option value="">Select...</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="subjectId" label="Subject">
                <option value="">Select...</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
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

    @if($showForm)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-6">
            <flux:heading size="lg" class="mb-4">Edit Grade</flux:heading>
            <form wire:submit.prevent="saveGrade" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <flux:input type="number" step="0.01" wire:model="written_works_score" label="Written Works (25%)" />
                <flux:input type="number" step="0.01" wire:model="performance_task_score" label="Performance Task (50%)" />
                <flux:input type="number" step="0.01" wire:model="quarterly_assessment_score" label="Quarterly Assessment (25%)" />
                <div class="sm:col-span-3 flex justify-end gap-2 mt-4">
                    <flux:button variant="ghost" wire:click="closeForm">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Save Grade</flux:button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm divide-y divide-zinc-100 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Written (25%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Performance (50%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Assessment (25%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Qtr Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Remarks</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($grades as $grade)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->student->last_name }}, {{ $grade->student->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->written_works_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->performance_task_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->quarterly_assessment_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium {{ $grade->quarter_grade >= 75 ? 'text-green-600' : ($grade->quarter_grade ? 'text-red-500' : '') }}">
                                {{ $grade->quarter_grade ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($grade->remarks === 'Passed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Passed</span>
                                @elseif($grade->remarks === 'Failed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Failed</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ $grade->remarks ?? 'Incomplete' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <flux:button size="sm" variant="ghost" wire:click="editGrade({{ $grade->id }})">Edit</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-zinc-500">
                                @if($schoolYearId && $sectionId && $subjectId && $quarter)
                                    No grades found.
                                @else
                                    Select filters to view grades.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
