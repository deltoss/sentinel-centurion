<?php

namespace Deltoss\Centurion\Http\Middleware;

use Closure;
use Sentinel;

class CenturionRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Sentinel::check();
        if ($user)
        {
            // User is logged in
            return redirect('/');
        }

        return $next($request);
    }
}
