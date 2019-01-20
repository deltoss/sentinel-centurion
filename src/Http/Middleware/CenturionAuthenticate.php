<?php

namespace Deltoss\Centurion\Http\Middleware;

use Closure;
use Sentinel;

class CenturionAuthenticate
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
        if (!$user)
        {
            // User is not logged in
            $redirectUrl = $request->fullUrl();

            // Redirect, putting the redirectUrl into the URL so the login page
            // can use this to redirect to the previous page after successful login
            //
            // Note we can flash the data into session to keep the URL clean,
            // however if the user decided to refresh the login page, and
            // then keep the redirect URL in a hidden input.
            // However the problem is the redirect URL would still disappear
            // when user refresh the login page through F5 or refresh button
            return redirect()->route('login.request', ['redirectUrl' => $redirectUrl]);
        }

        return $next($request);
    }
}
