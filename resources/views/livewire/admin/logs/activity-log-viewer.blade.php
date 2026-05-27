<div>
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item>Activity Logs</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium text-zinc-800 dark:text-zinc-100">Activity Logs</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Full audit trail of all system actions.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search description or action…"
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="filterModule" placeholder="All modules">
                <flux:select.option value="">All Modules</flux:select.option>
                <flux:select.option value="enrollment">Enrollment</flux:select.option>
                <flux:select.option value="student">Student</flux:select.option>
                <flux:select.option value="grades">Grades</flux:select.option>
                <flux:select.option value="teacher">Teacher</flux:select.option>
                <flux:select.option value="academic">Academic</flux:select.option>
                <flux:select.option value="announcements">Announcements</flux:select.option>
                <flux:select.option value="settings">Settings</flux:select.option>
                <flux:select.option value="accounts">Accounts</flux:select.option>
                <flux:select.option value="auth">Auth</flux:select.option>
            </flux:select>

            <flux:select wire:model.live="filterUser" placeholder="All users">
                <flux:select.option value="">All Users</flux:select.option>
                @foreach($users as $user)
                    <flux:select.option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex gap-2">
                <flux:input type="date" wire:model.live="dateFrom" placeholder="From" class="flex-1" />
                <flux:input type="date" wire:model.live="dateTo" placeholder="To" class="flex-1" />
            </div>
        </div>

        @if($search || $filterModule || $filterUser || $dateFrom || $dateTo)
            <div class="mt-3 flex justify-end">
                <flux:button wire:click="clearFilters" variant="ghost" size="sm" icon="x-mark">
                    Clear filters
                </flux:button>
            </div>
        @endif
    </div>

    {{-- Table --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        @if($logs->isEmpty())
            <div class="px-5 py-4">
                <x-empty-state 
                    icon="ti ti-clipboard-x"
                    title="No activity logs found"
                />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-36">Timestamp</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-40">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-28">Module</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-40">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider w-28">IP Address</th>
                            <th class="px-4 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @foreach($logs as $log)
                            @php
                                $moduleBadge = match($log->module) {
                                    'enrollment'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'student'       => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'grades'        => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'teacher'       => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'academic'      => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
                                    'announcements' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'settings'      => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300',
                                    'accounts'      => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                    'auth'          => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default         => 'bg-zinc-100 text-zinc-500',
                                };
                                $isExpanded = in_array($log->id, $expandedRows);
                            @endphp

                            <tr
                                wire:click="toggleRow({{ $log->id }})"
                                wire:key="log-{{ $log->id }}"
                                class="hover:bg-zinc-50 dark:hover:bg-zinc-700/40 cursor-pointer transition-colors"
                            >
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-zinc-400 dark:text-zinc-500">
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <div>{{ $log->created_at->format('h:i:s A') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $log->user->name }}</div>
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-xs bg-zinc-100 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400 capitalize">{{ $log->user->role }}</span>
                                    @else
                                        <span class="text-zinc-400 text-xs italic">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $moduleBadge }}">
                                        {{ ucfirst($log->module) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <code class="text-xs bg-zinc-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded text-zinc-600 dark:text-zinc-300">{{ $log->action }}</code>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300">
                                    {{ $log->description }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-zinc-400 dark:text-zinc-500 font-mono">
                                    {{ $log->ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($log->old_values || $log->new_values)
                                        <i class="ti ti-chevron-{{ $isExpanded ? 'up' : 'down' }} text-zinc-400" style="font-size:14px"></i>
                                    @endif
                                </td>
                            </tr>

                            {{-- Expanded Details Row --}}
                            @if($isExpanded && ($log->old_values || $log->new_values))
                                <tr wire:key="log-expanded-{{ $log->id }}" class="bg-zinc-50 dark:bg-zinc-900/60">
                                    <td colspan="7" class="px-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @if($log->old_values)
                                                <div>
                                                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Previous Values</p>
                                                    <pre class="font-mono text-xs bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 text-red-700 dark:text-red-300 rounded-lg p-3 overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                            @if($log->new_values)
                                                <div>
                                                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">New Values</p>
                                                    <pre class="font-mono text-xs bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 text-green-700 dark:text-green-300 rounded-lg p-3 overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
