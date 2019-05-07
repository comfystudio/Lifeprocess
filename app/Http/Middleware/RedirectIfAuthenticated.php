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
            if($request->segment('1') == 'backtoadmin' && \Session::has('admin_user_id')){
                return $next($request);
            }else if (Auth::user()->user_type == 'user') {
                return redirect('/dashboard');
            } else if (Auth::user()->user_type == 'client') {
                return redirect('/client-dashboard');
            } else if (Auth::user()->user_type == 'coach') {
                return redirect('/coach-dashboard');
            }
            // return redirect('/dashboard');
        }

        return $next($request);
    }
}
