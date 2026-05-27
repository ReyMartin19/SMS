<div>
    @if (session('success'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 
            dark:text-green-300 rounded-xl text-sm flex items-center 
            justify-between gap-3"
        >
            <div class="flex items-center gap-2">
                <i class="ti ti-circle-check" style="font-size:16px"></i>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <i class="ti ti-x" style="font-size:14px"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 
            dark:text-red-300 rounded-xl text-sm flex items-center 
            justify-between gap-3"
        >
            <div class="flex items-center gap-2">
                <i class="ti ti-alert-circle" style="font-size:16px"></i>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <i class="ti ti-x" style="font-size:14px"></i>
            </button>
        </div>
    @endif
</div>
