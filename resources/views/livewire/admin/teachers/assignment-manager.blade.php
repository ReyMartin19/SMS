<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item>Teachers</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Assignments</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Teacher Assignments</h1>
            <p class="text-sm text-zinc-500">Assign teachers to subjects and sections.</p>
        </div>
        
        <div>
            <flux:button wire:click="create" icon="plus">New Assignment</flux:button>
        </div>
    </div>

    <x-flash-message />

    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="mb-4 text-lg font-medium">Create Assignment</h2>
            
            <form wire:submit="save" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:select wire:model="school_year_id" label="School Year">
                    <option value="">Select School Year</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="teacher_id" label="Teacher">
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="subject_id" label="Subject">
                    <option value="" class="bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">Select Subject</option>
                    @foreach($subjects as $groupName => $groupSubjects)
                        <optgroup label="{{ $groupName }}" class="bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 font-semibold">
                            @foreach($groupSubjects as $subject)
                                <option value="{{ $subject->id }}" class="bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300">
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="section_id" label="Section">
                    <option value="" class="bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">Select Section</option>
                    @foreach($sections as $gradeLevelName => $gradeSections)
                        <optgroup label="{{ $gradeLevelName }}" class="bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 font-semibold">
                            @foreach($gradeSections as $section)
                                <option value="{{ $section->id }}" class="bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300">
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </flux:select>
                
                <div class="col-span-1 flex justify-end gap-2 sm:col-span-2">
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Assignment</flux:button>
                </div>
            </form>
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
        <div class="border-b border-zinc-200 p-4 dark:border-zinc-700 flex flex-col gap-4 sm:flex-row">
            <flux:select wire:model.live="schoolYearFilter" class="max-w-xs">
                <option value="">All School Years</option>
                @foreach($schoolYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}</option>
                @endforeach
            </flux:select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 text-xs uppercase tracking-wider text-zinc-400 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 font-medium">Teacher</th>
                        <th class="px-6 py-3 font-medium">Subject</th>
                        <th class="px-6 py-3 font-medium">Section</th>
                        <th class="px-6 py-3 font-medium">School Year</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse ($assignments as $assignment)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="whitespace-nowrap px-6 py-4 font-medium">
                                {{ $assignment->teacher->first_name ?? '' }} {{ $assignment->teacher->last_name ?? '' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $assignment->subject->name ?? '' }} 
                                <span class="text-xs text-zinc-500">({{ $assignment->subject->code ?? '' }})</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $assignment->section->gradeLevel->name ?? '' }} - {{ $assignment->section->name ?? '' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $assignment->schoolYear->name ?? '' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <flux:button wire:click="delete({{ $assignment->id }})" wire:confirm="Are you sure you want to delete this? This action cannot be undone." variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4">
                                <x-empty-state 
                                    icon="ti ti-users-off"
                                    title="No assignments found"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</div>
