<div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium">Grade Levels</h1>
            <p class="text-sm text-zinc-500 mt-1">Manage grade levels for all school types.</p>
        </div>
        @if (!$showForm)
            <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                <i class="ti ti-plus"></i> Add Grade Level
            </button>
        @endif
    </div>

    <x-flash-message />

    {{-- Form --}}
    @if ($showForm)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-4">
            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">
                {{ $editingId ? 'Edit Grade Level' : 'New Grade Level' }}
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Name <span class="text-red-400">*</span></label>
                    <flux:input wire:model="name" placeholder="e.g. Grade 7" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Order <span class="text-red-400">*</span></label>
                    <flux:input type="number" wire:model="order" min="1" />
                    @error('order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 mb-1">Type <span class="text-red-400">*</span></label>
                    <flux:select wire:model="type">
                        <option value="elementary">Elementary</option>
                        <option value="junior_high">Junior High</option>
                        <option value="senior_high">Senior High</option>
                    </flux:select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

    {{-- Table --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-700 text-xs text-zinc-400 uppercase tracking-wider">
                    <th class="text-left px-5 py-3 font-medium">Name</th>
                    <th class="text-left px-5 py-3 font-medium">Order</th>
                    <th class="text-left px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($gradeLevels as $level)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-5 py-3 font-medium text-zinc-700 dark:text-zinc-200">{{ $level->name }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $level->order }}</td>
                        <td class="px-5 py-3">
                            @php
                                $typeColors = [
                                    'elementary'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'junior_high' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'senior_high' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                ];
                                $typeColor = $typeColors[$level->type] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColor }}">
                                {{ ucfirst(str_replace('_', ' ', $level->type)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right flex items-center justify-end gap-3">
                            <button wire:click="openEdit({{ $level->id }})" class="text-xs text-blue-500 hover:text-blue-700">Edit</button>
                            <button wire:click="delete({{ $level->id }})" wire:confirm="Are you sure you want to delete this? This action cannot be undone." class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-4">
                            <x-empty-state 
                                icon="ti ti-school-off"
                                title="No grade levels found"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>