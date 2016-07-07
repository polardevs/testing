<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ProjectManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user() &&
           Auth::user()->permission &&
           Auth::user()->permission->role &&
           Auth::user()->permission->role->isProjectManager())
            return $next($request);

        return response()->view('errors.404');
    }
}
