<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item>Teachers</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Subjects</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Subjects</h1>
            <p class="text-sm text-zinc-500">Manage school subjects and their categories.</p>
        </div>
        
        <div>
            <flux:button wire:click="create" icon="plus">Add Subject</flux:button>
        </div>
    </div>

    <x-flash-message />

    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="mb-4 text-lg font-medium">{{ $subjectId ? 'Edit Subject' : 'Add Subject' }}</h2>
            
            <form wire:submit="save" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:input wire:model="name" label="Subject Name" placeholder="e.g. Mathematics" />
                <flux:input wire:model="code" label="Subject Code" placeholder="e.g. MATH7" />
                
                <flux:select wire:model="type" label="Type">
                    <option value="">Select Type</option>
                    <option value="elementary">Elementary</option>
                    <option value="junior_high">Junior High</option>
                    <option value="senior_high">Senior High</option>
                </flux:select>
                
                <flux:select wire:model="grade_level_id" label="Grade Level (Optional)">
                    <option value="">Any/None</option>
                    @foreach($gradeLevels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </flux:select>
                
                <div class="col-span-1 flex justify-end gap-2 sm:col-span-2">
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Subject</flux:button>
                </div>
            </form>
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
        <div class="border-b border-zinc-200 p-4 dark:border-zinc-700 flex flex-col gap-4 sm:flex-row">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search subjects..." class="max-w-sm" />
            
            <flux:select wire:model.live="typeFilter" class="max-w-xs">
                <option value="">All Types</option>
                <option value="elementary">Elementary</option>
                <option value="junior_high">Junior High</option>
                <option value="senior_high">Senior High</option>
            </flux:select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 text-xs uppercase tracking-wider text-zinc-400 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 font-medium">Code</th>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Type</th>
                        <th class="px-6 py-3 font-medium">Grade Level</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse ($subjects as $subject)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $subject->code }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $subject->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($subject->type === 'elementary')
                                    <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">Elementary</span>
                                @elseif($subject->type === 'junior_high')
                                    <span class="rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/20 dark:text-purple-400">Junior High</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/20 dark:text-amber-400">Senior High</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-zinc-500">
                                {{ $subject->gradeLevel ? $subject->gradeLevel->name : '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <flux:button wire:click="edit({{ $subject->id }})" variant="ghost" size="sm" icon="pencil-square" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300" />
                                <flux:button wire:click="delete({{ $subject->id }})" wire:confirm="Are you sure you want to delete this? This action cannot be undone." variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4">
                                <x-empty-state 
                                    icon="ti ti-users-off"
                                    title="No subjects found"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subjects->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</div>
