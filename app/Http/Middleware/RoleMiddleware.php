<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! in_array(auth()->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            if (auth()->user()->isKaryawan()) {
                return redirect()->route('karyawan.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('owner.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
