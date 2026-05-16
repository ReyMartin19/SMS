<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium">School Years</h1>
            <p class="text-sm text-zinc-500 mt-1">Manage academic school years.</p>
        </div>
        @if (!$showForm)
            <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                <i class="ti ti-plus"></i> Add School Year
            </button>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    @if ($showForm)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-4">
            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">
                {{ $editingId ? 'Edit School Year' : 'New School Year' }}
            </p>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Name <span class="text-red-400">*</span></label>
                    <flux:input wire:model="name" placeholder="e.g. 2025-2026" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Start date <span class="text-red-400">*</span></label>
                    <flux:input type="date" wire:model="start_date" />
                    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">End date <span class="text-red-400">*</span></label>
                    <flux:input type="date" wire:model="end_date" />
                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <flux:checkbox wire:model="is_active" id="is_active" />
                <label for="is_active" class="text-sm text-zinc-600 dark:text-zinc-300">Set as active school year</label>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <flux:button variant="ghost" wire:click="cancel">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save">Save</flux:button>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-700 text-xs text-zinc-400 uppercase tracking-wider">
                    <th class="text-left px-5 py-3 font-medium">Name</th>
                    <th class="text-left px-5 py-3 font-medium">Start date</th>
                    <th class="text-left px-5 py-3 font-medium">End date</th>
                    <th class="text-left px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($schoolYears as $year)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-5 py-3 font-medium text-zinc-700 dark:text-zinc-200">{{ $year->name }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $year->start_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $year->end_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            @if ($year->is_active)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                            @else
                                <button wire:click="setActive({{ $year->id }})" class="text-xs text-zinc-400 hover:text-blue-500">Set active</button>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right flex items-center justify-end gap-3">
                            <button wire:click="openEdit({{ $year->id }})" class="text-xs text-blue-500 hover:text-blue-700">Edit</button>
                            <button wire:click="delete({{ $year->id }})" wire:confirm="Delete this school year?" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-zinc-400">
                            <i class="ti ti-calendar-off" style="font-size:32px; display:block; margin-bottom:8px"></i>
                            No school years found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>