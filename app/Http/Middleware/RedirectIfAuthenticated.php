<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if($request->segment(1) == 'password' && $request->segment(2) == 'reset' && Auth::user()->permission && Auth::user()->permission->role->isAdmin())
                return $next($request);

            return redirect('/');
        }

        return $next($request);
    }
}
