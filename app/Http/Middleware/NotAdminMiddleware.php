<?php

namespace App\Http\Middleware;

use Closure;

class NotAdminMiddleware
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
        if (auth()->check() && auth()->user()->rol == 4) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
