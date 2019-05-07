<?php


namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\User;
use Flash;
use Carbon\Carbon;
use Session;
use Cache;

class LogLastUserActivity
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
        if(Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(5);

            // Store current user's timezone in session
            Session::put('current_user_timezone', Auth::user()->timezone);
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
        }
        return $next($request);
    }
}
