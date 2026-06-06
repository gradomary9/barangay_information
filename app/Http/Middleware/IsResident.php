<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsResident
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect('register');
        }
        if (!$request->user()) {
            return redirect('login');
        }

        if ($request->user()->role !== 'resident') {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
