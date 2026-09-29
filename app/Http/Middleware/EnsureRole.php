<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Allow access only to users whose role (from the authenticated session /
     * database) is listed in the middleware parameters.
     *
     * Usage: ->middleware('role:super_admin,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if($user === null, 403, 'Anda tidak memiliki akses ke halaman ini.');

        $allowed = array_map(
            fn (string $role) => trim($role),
            $roles,
        );

        if (! in_array($user->role?->value, $allowed, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menjalankan aksi ini.');
        }

        return $next($request);
    }
}
