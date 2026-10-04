<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            return response()->json([
                'message' => 'This procedure is only for admins',
            ], 403);
        }

        return $next($request);
    }
}
