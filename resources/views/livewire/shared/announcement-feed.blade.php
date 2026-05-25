<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <flux:breadcrumbs class="mb-2">
            <flux:breadcrumbs.item href="#" icon="home" />
            <flux:breadcrumbs.item>Announcements</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <h1 class="text-xl font-medium">Announcements</h1>
        <p class="text-sm text-zinc-500 mt-1">Stay updated with the latest news, events, and updates from the school administration.</p>
    </div>

    {{-- Announcements Feed list --}}
    <div class="space-y-4">
        @forelse ($announcements as $announcement)
            @php
                // Pinned border styling
                $cardClasses = 'bg-white dark:bg-zinc-800 border rounded-xl p-5 shadow-sm transition hover:shadow-md ';
                if ($announcement->is_pinned) {
                    $cardClasses .= 'border-zinc-200 dark:border-zinc-700 border-l-4 border-l-amber-500';
                } else {
                    $cardClasses .= 'border-zinc-200 dark:border-zinc-700';
                }

                // Audience colors
                $audienceColors = [
                    'all'     => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
                    'admin'    => 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800',
                    'teacher'  => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                    'student'  => 'bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800',
                    'parent'   => 'bg-pink-50 text-pink-700 dark:bg-pink-950/30 dark:text-pink-400 border border-pink-200 dark:border-pink-800',
                ];
                $audienceColor = $audienceColors[$announcement->audience] ?? 'bg-zinc-50 text-zinc-700 border border-zinc-200';

                $isExpanded = $expandedAnnouncements[$announcement->id] ?? false;
            @endphp
            <div class="{{ $cardClasses }}">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        @if ($announcement->is_pinned)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                <i class="ti ti-pin text-amber-500"></i> Pinned
                            </span>
                        @endif
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                            {{ $announcement->title }}
                        </h3>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $audienceColor }}">
                            Target: {{ ucfirst($announcement->audience) }}
                        </span>
                    </div>
                </div>

                {{-- Author & Timestamp --}}
                <div class="flex items-center gap-2 text-xs text-zinc-400 dark:text-zinc-500 mb-4 font-medium">
                    <div class="flex items-center gap-1">
                        <i class="ti ti-user text-sm"></i>
                        <span>{{ $announcement->author?->name ?? 'System Admin' }}</span>
                    </div>
                    <span>•</span>
                    <div class="flex items-center gap-1">
                        <i class="ti ti-calendar text-sm"></i>
                        <span>{{ $announcement->published_at ? $announcement->published_at->format('M d, Y h:i A') : '—' }}</span>
                    </div>
                </div>

                {{-- Content Body with inline toggle --}}
                <div class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                    @if ($isExpanded)
                        {{ $announcement->body }}
                    @else
                        {{ Str::limit($announcement->body, 150) }}
                    @endif
                </div>

                {{-- Action toggle expand button --}}
                @if (strlen($announcement->body) > 150)
                    <div class="mt-4 flex justify-start">
                        <button wire:click="toggleExpand({{ $announcement->id }})" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 border border-blue-200 hover:border-blue-300 dark:border-blue-800/80 dark:hover:border-blue-700/80 bg-blue-50/50 hover:bg-blue-50 dark:bg-blue-900/10 dark:hover:bg-blue-900/20 px-3 py-1.5 rounded-lg transition shadow-inner">
                            @if ($isExpanded)
                                <i class="ti ti-chevron-up"></i> Show Less
                            @else
                                <i class="ti ti-chevron-down"></i> Read More
                            @endif
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center text-zinc-400 shadow-sm">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                        <i class="ti ti-megaphone text-3xl text-zinc-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-700 dark:text-zinc-300">No Announcements</h3>
                    <p class="text-sm mt-1 max-w-sm">There are no active announcements matching your role at this moment. Please check back later!</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($announcements->hasPages())
            <div class="mt-6">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</div>
