<div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium">Sections</h1>
            <p class="text-sm text-zinc-500 mt-1">Manage sections per grade level.</p>
        </div>
        @if (!$showForm)
            <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                <i class="ti ti-plus"></i> Add Section
            </button>
        @endif
    </div>

    <x-flash-message />

    {{-- Form --}}
    @if ($showForm)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-4">
            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">
                {{ $editingId ? 'Edit Section' : 'New Section' }}
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Grade level <span class="text-red-400">*</span></label>
                    <flux:select wire:model="grade_level_id">
                        <option value="">Select grade level</option>
                        @foreach ($gradeLevels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('grade_level_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Section name <span class="text-red-400">*</span></label>
                    <flux:input wire:model="name" placeholder="e.g. Sampaguita" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Room number</label>
                    <flux:input wire:model="room_number" placeholder="e.g. Room 101" />
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Capacity</label>
                    <flux:input type="number" wire:model="capacity" placeholder="e.g. 40" min="1" />
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <flux:button variant="ghost" wire:click="cancel">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled">
                    <i class="ti ti-check" wire:loading.remove wire:target="save"></i>
                    <flux:icon.loading wire:loading wire:target="save" class="w-4 h-4 mr-1" />
                    Save
                </flux:button>
            </div>
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 mb-4">
        <div class="w-64">
            <flux:select wire:model.live="gradeLevelFilter">
                <option value="">All grade levels</option>
                @foreach ($gradeLevels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Table --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-700 text-xs text-zinc-400 uppercase tracking-wider">
                    <th class="text-left px-5 py-3 font-medium">Section</th>
                    <th class="text-left px-5 py-3 font-medium">Grade level</th>
                    <th class="text-left px-5 py-3 font-medium">Room</th>
                    <th class="text-left px-5 py-3 font-medium">Capacity</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($sections as $section)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-5 py-3 font-medium text-zinc-700 dark:text-zinc-200">{{ $section->name }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $section->gradeLevel->name }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $section->room_number ?? '—' }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $section->capacity ?? '—' }}</td>
                        <td class="px-5 py-3 text-right flex items-center justify-end gap-3">
                            <button wire:click="openEdit({{ $section->id }})" class="text-xs text-blue-500 hover:text-blue-700">Edit</button>
                            <button wire:click="delete({{ $section->id }})" wire:confirm="Are you sure you want to delete this? This action cannot be undone." class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-4">
                            <x-empty-state 
                                icon="ti ti-layout-off"
                                title="No sections found"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>