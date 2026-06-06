<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (strtolower($request->user()->role ?? '') !== 'admin') {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}