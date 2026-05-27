<div>
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-xs text-zinc-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center hover:text-blue-600">
                    <i class="ti ti-smart-home mr-1"></i> Parent
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-blue-600 ml-1 md:ml-2">Dashboard</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">Announcements</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-xl font-medium text-zinc-900 dark:text-white flex items-center gap-2">
            <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                <i class="ti ti-megaphone text-xl"></i>
            </div>
            Announcements Board
        </h1>
        <p class="text-sm text-zinc-500 mt-1">
            Stay updated with the latest updates, parent-teacher association notices, and news broadcasts.
        </p>
    </div>

    {{-- Announcements Feed list --}}
    <div class="space-y-4 max-w-4xl">
        @if ($announcements->isEmpty())
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center text-zinc-400 shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                    <i class="ti ti-bell-off text-3xl opacity-50"></i>
                </div>
                <p class="text-sm font-medium">No announcements found</p>
                <p class="text-xs mt-1 text-zinc-550">There are no active parent or general broadcasts at this moment.</p>
            </div>
        @else
            @foreach ($announcements as $announcement)
                @php
                    $isExpanded = in_array($announcement->id, $expandedAnnouncements);
                @endphp
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm transition-all duration-200 relative overflow-hidden group hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-700 {{ $announcement->is_pinned ? 'border-l-4 border-l-amber-400 dark:border-l-amber-400' : '' }}">
                    
                    {{-- Title Header --}}
                    <div class="flex justify-between items-start gap-4 mb-3">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-base font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-1.5 flex-wrap">
                                @if ($announcement->is_pinned)
                                    <span class="inline-flex items-center text-amber-500 font-medium" title="Pinned Announcement">
                                        <i class="ti ti-pin text-base mr-0.5"></i>
                                    </span>
                                @endif
                                {{ $announcement->title }}
                            </h2>
                            
                            {{-- Metadata --}}
                            <div class="flex items-center gap-3 mt-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                                <span class="flex items-center gap-1">
                                    <i class="ti ti-calendar text-xs"></i>
                                    {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="ti ti-user text-xs"></i>
                                    Posted by: {{ $announcement->author->name ?? 'Administrator' }}
                                </span>
                                <span>•</span>
                                @if ($announcement->audience === 'all')
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-950/40">
                                        All
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-pink-50 dark:bg-pink-950/20 text-pink-700 dark:text-pink-400 border border-pink-100 dark:border-pink-950/40">
                                        Parent
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Toggler --}}
                        <button 
                            wire:click="toggleAnnouncement({{ $announcement->id }})" 
                            class="text-zinc-400 hover:text-zinc-650 dark:hover:text-zinc-200 shrink-0 p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                            aria-label="Toggle announcement details"
                        >
                            <i class="ti ti-chevron-{{ $isExpanded ? 'up' : 'down' }} text-lg"></i>
                        </button>
                    </div>

                    {{-- Body content --}}
                    <div class="text-sm text-zinc-650 dark:text-zinc-400 leading-relaxed">
                        @if ($isExpanded)
                            <div class="whitespace-pre-line prose prose-sm dark:prose-invert mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/50">
                                {{ $announcement->body }}
                            </div>
                        @else
                            <p class="line-clamp-2">
                                {{ strip_tags($announcement->body) }}
                            </p>
                        @endif
                    </div>

                </div>
            @endforeach
        @endif
    </div>
</div>
