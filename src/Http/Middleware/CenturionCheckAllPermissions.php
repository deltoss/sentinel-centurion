<?php

namespace Deltoss\Centurion\Http\Middleware;

use Closure;
use Sentinel;

class CenturionCheckAllPermissions
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
        $permissionSlugs = array_slice(func_get_args(), 2); // Get all arguments from 3rd argument and onwards
        $hasAccess = Sentinel::hasAccess($permissionSlugs);

        if (!$hasAccess)
            return redirect()->route('unauthorised');

        return $next($request);
    }
}
