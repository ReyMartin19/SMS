<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item>Teachers</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Teachers</h1>
            <p class="text-sm text-zinc-500">Manage teaching staff and their profiles.</p>
        </div>
        
        <div>
            <flux:button href="{{ route('admin.teachers.create') }}" wire:navigate icon="plus">Add Teacher</flux:button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 rounded-xl bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
        <div class="border-b border-zinc-200 p-4 dark:border-zinc-700 flex flex-col gap-4 sm:flex-row">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search teachers..." class="max-w-sm" />
            
            <flux:select wire:model.live="statusFilter" class="max-w-xs">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 text-xs uppercase tracking-wider text-zinc-400 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 font-medium">ID / Name</th>
                        <th class="px-6 py-3 font-medium">Gender</th>
                        <th class="px-6 py-3 font-medium">Contact</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse ($teachers as $teacher)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $teacher->first_name }} {{ $teacher->last_name }} {{ $teacher->suffix }}
                                </div>
                                <div class="text-xs text-zinc-500">{{ $teacher->employee_id ?? 'No ID' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 capitalize">{{ $teacher->gender }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $teacher->contact_number ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($teacher->status === 'active')
                                    <span class="rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Active</span>
                                @else
                                    <span class="rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400">Inactive</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <flux:button href="{{ route('admin.teachers.show', $teacher) }}" wire:navigate variant="ghost" size="sm" icon="eye" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300" />
                                <flux:button wire:click="delete({{ $teacher->id }})" wire:confirm="Are you sure you want to delete this teacher?" variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                No teachers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
