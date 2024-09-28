<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordChanged
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->password_changed) {
            return response()->json(['message' => 'Changement de mot de passe requis'], 403);
        }
        return $next($request);
    }
}