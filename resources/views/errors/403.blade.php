<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center flex flex-col items-center justify-center">
            <i class="ti ti-lock text-zinc-300 dark:text-zinc-600 mb-4" style="font-size: 64px;"></i>
            <h1 class="text-2xl font-medium text-zinc-700 dark:text-zinc-200">Access Denied</h1>
            <p class="text-sm text-zinc-400 mt-2">You don't have permission to access this page.</p>
            <a href="{{ url()->previous() == url()->current() ? route('dashboard') : url()->previous() }}" class="mt-6 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                Go Back
            </a>
        </div>
    </body>
</html>
