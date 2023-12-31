<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user() && Auth::user()->is_admin == 1 || $request->expectsJson()) {
            return $next($request);
        }
        return back();
        // return response('Unauthorized.', 401);
        //abort(401);

        // return redirect()->back()->with('unauthorised', 'You are page');


    }
}
