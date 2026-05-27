<div class="flex flex-col items-center justify-center py-16 text-center">
    <i class="{{ $icon }} text-zinc-300 dark:text-zinc-600 mb-4" style="font-size:48px"></i>
    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $title }}</p>
    @if(isset($description))
        <p class="text-xs text-zinc-400 mt-1">{{ $description }}</p>
    @endif
    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
