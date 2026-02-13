<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->isKasir()) {
            return redirect()
                ->route('login')
                ->with('error', 'Anda tidak memiliki akses ke halaman kasir');
        }

        return $next($request);
    }
}
