<div>
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item>Announcements</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Announcements</h1>
            <p class="text-sm text-zinc-500">Create, manage, and target announcements to different school roles.</p>
        </div>
        
        @if (!$showForm)
            <div>
                <flux:button wire:click="openCreate" icon="plus" variant="primary">Add Announcement</flux:button>
            </div>
        @endif
    </div>

    {{-- Success Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-xl text-sm flex items-center gap-2">
            <i class="ti ti-circle-check text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Inline Create/Edit Form --}}
    @if ($showForm)
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 mb-6 shadow-sm">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-4">
                {{ $editingId ? 'Edit Announcement' : 'New Announcement' }}
            </p>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="md:col-span-2">
                    <flux:input wire:model="title" label="Title *" placeholder="e.g. System Maintenance Schedule" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <flux:select wire:model="audience" label="Target Audience *">
                        <option value="all">All (Everyone)</option>
                        <option value="admin">Admins Only</option>
                        <option value="teacher">Teachers Only</option>
                        <option value="student">Students Only</option>
                        <option value="parent">Parents Only</option>
                    </flux:select>
                    @error('audience') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-3">
                    <flux:textarea wire:model="body" label="Body Content *" rows="5" placeholder="Write the announcement details here..." />
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <flux:input type="datetime-local" wire:model="published_at" label="Published Date" />
                    <p class="text-[11px] text-zinc-400 mt-1">Leave empty or set in the future to keep as draft.</p>
                    @error('published_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <flux:input type="date" wire:model="expiry_date" label="Expiry Date" />
                    <p class="text-[11px] text-zinc-400 mt-1">Optional. The announcement will be hidden after this date.</p>
                    @error('expiry_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="is_pinned" class="rounded border-zinc-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-zinc-700 dark:bg-zinc-900" />
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300 flex items-center gap-1">
                            <i class="ti ti-pin text-amber-500"></i> Pin this announcement to top
                        </span>
                    </label>
                    @error('is_pinned') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                <flux:button variant="ghost" wire:click="cancel">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save">Save Announcement</flux:button>
            </div>
        </div>
    @endif

    {{-- Filter and Search Bar --}}
    <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-80">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by title..." icon="magnifying-glass" size="sm" />
        </div>
        
        <div class="w-full md:w-auto flex flex-wrap gap-3 items-center justify-end">
            <div>
                <flux:select wire:model.live="filterAudience" size="sm" class="min-w-[120px]">
                    <option value="">All Audiences</option>
                    <option value="all">Everyone</option>
                    <option value="admin">Admins</option>
                    <option value="teacher">Teachers</option>
                    <option value="student">Students</option>
                    <option value="parent">Parents</option>
                </flux:select>
            </div>

            <div>
                <flux:select wire:model.live="filterStatus" size="sm" class="min-w-[120px]">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="expired">Expired</option>
                </flux:select>
            </div>

            <div>
                <flux:select wire:model.live="filterPinned" size="sm" class="min-w-[120px]">
                    <option value="">All Pinned</option>
                    <option value="1">Pinned Only</option>
                    <option value="0">Unpinned Only</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Announcement Data Table --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 text-xs text-zinc-400 uppercase tracking-wider bg-zinc-50/50 dark:bg-zinc-900/30">
                    <th class="text-left px-5 py-3 font-medium">Title</th>
                    <th class="text-left px-5 py-3 font-medium">Author</th>
                    <th class="text-left px-5 py-3 font-medium">Audience</th>
                    <th class="text-left px-5 py-3 font-medium">Published At</th>
                    <th class="text-left px-5 py-3 font-medium">Expiry Date</th>
                    <th class="text-left px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($announcements as $announcement)
                    @php
                        // Status logic
                        $now = now();
                        $today = today();
                        $isDraft = !$announcement->published_at || $announcement->published_at > $now;
                        $isExpired = $announcement->expiry_date && $announcement->expiry_date < $today;
                        
                        if ($isExpired) {
                            $statusLabel = 'Expired';
                            $statusColor = 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 border border-red-200 dark:border-red-800';
                        } elseif ($isDraft) {
                            $statusLabel = 'Draft';
                            $statusColor = 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700';
                        } else {
                            $statusLabel = 'Active';
                            $statusColor = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
                        }

                        // Audience color
                        $audienceColors = [
                            'all'     => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
                            'admin'    => 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800',
                            'teacher'  => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                            'student'  => 'bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800',
                            'parent'   => 'bg-pink-50 text-pink-700 dark:bg-pink-950/30 dark:text-pink-400 border border-pink-200 dark:border-pink-800',
                        ];
                        $audienceColor = $audienceColors[$announcement->audience] ?? 'bg-zinc-50 text-zinc-700 border border-zinc-200';
                    @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        <td class="px-5 py-4 font-medium text-zinc-900 dark:text-white max-w-xs">
                            <div class="flex items-center gap-2">
                                @if ($announcement->is_pinned)
                                    <span class="inline-flex items-center justify-center bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-md p-1 border border-amber-200 dark:border-amber-800 shrink-0" title="Pinned to Top">
                                        <i class="ti ti-pin"></i>
                                    </span>
                                @endif
                                <span class="truncate" title="{{ $announcement->title }}">{{ $announcement->title }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-zinc-500 dark:text-zinc-400">{{ $announcement->author?->name ?? 'System' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $audienceColor }}">
                                {{ ucfirst($announcement->audience) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-zinc-500 dark:text-zinc-400">
                            {{ $announcement->published_at ? $announcement->published_at->format('M d, Y h:i A') : '—' }}
                        </td>
                        <td class="px-5 py-4 text-zinc-500 dark:text-zinc-400">
                            {{ $announcement->expiry_date ? $announcement->expiry_date->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="openEdit({{ $announcement->id }})" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold flex items-center gap-1">
                                    <i class="ti ti-edit"></i> Edit
                                </button>
                                <button wire:click="delete({{ $announcement->id }})" wire:confirm="Are you sure you want to delete this announcement?" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-semibold flex items-center gap-1">
                                    <i class="ti ti-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-zinc-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                                    <i class="ti ti-megaphone-off text-2xl text-zinc-400"></i>
                                </div>
                                <p class="text-sm font-medium">No announcements found.</p>
                                <p class="text-xs mt-1">Once created, targeted announcements will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($announcements->hasPages())
            <div class="px-5 py-4 border-t border-zinc-100 dark:border-zinc-700">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</div>
