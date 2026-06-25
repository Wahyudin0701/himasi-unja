<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (in_array(auth()->user()->global_role, $roles) || auth()->user()->global_role === 'super_admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
