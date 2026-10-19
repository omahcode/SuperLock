<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        abort_unless($user, 401, 'Login diperlukan');

        if ($roles !== [] && ! in_array($user->role, $roles, true)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(403, 'Akses ditolak untuk peran Anda.');
            }

            $defaultRoute = $user->role === 'km' ? 'km' : 'monitoring';

            if (! $request->routeIs($defaultRoute)) {
                return redirect()->route($defaultRoute)->with('pesan', 'Akses ditolak untuk peran Anda pada halaman tersebut.');
            }

            abort(403, 'Akses ditolak untuk peran Anda.');
        }

        return $next($request);
    }
}
