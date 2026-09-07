<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isOwner()) {
                return redirect()->route('owner.dashboard');
            }

            return redirect()->route('karyawan.dashboard');
        }

        return $next($request);
    }
}
