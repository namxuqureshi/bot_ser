<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAdminAuthentication
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
        if(session('name') != null) {
            return redirect('/admin');
        }
        return $next($request);
    }
}
