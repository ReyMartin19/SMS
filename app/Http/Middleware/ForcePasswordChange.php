<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check() &&
            auth()->user()->force_password_change &&
            ! $request->routeIs('password.change') &&
            ! $request->routeIs('logout') &&
            ! $request->routeIs('livewire.*') &&
            ! $request->routeIs('default-livewire.*')
        ) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
