<?php

namespace Deltoss\Centurion\Http\Middleware;

use Closure;
use Sentinel;

class CenturionCheckRole
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
        $roleSlugs = array_slice(func_get_args(), 2); // Get all arguments from 3rd argument and onwards
        $hasAccess = false;

        // If user is in any of the roles, they have access
        foreach($roleSlugs as $roleSlug)
        {
            $inRole = Sentinel::inRole($roleSlug);
            if ($inRole)
            {
                $hasAccess = true;
                break;
            }
        }
        if (!$hasAccess)
            return redirect()->route('unauthorised');

        return $next($request);
    }
}
