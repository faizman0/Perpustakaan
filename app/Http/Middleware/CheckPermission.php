<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if (!$request->user()->hasPermission($permission)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}