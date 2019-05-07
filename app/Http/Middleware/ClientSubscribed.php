<?php

namespace App\Http\Middleware;

use Closure;
use Flash;
use Auth;
use App\Models\Client;

class ClientSubscribed
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
        $user = Auth::user();
        if ($user->subscription_plan_status) {
            if(Auth::check() && $user->user_type  == 'client') {
                if($user->subscription_plan_status == 'Expired') {
                    Flash::warning('Your Subscription or Card details are expired. Please update your card details');
                    return redirect()->route('clients.update.profile');
                } else if($user->subscription_plan_status != 'Active') {
                    Flash::warning('Your Subscription is ' . ($user->subscription_plan_status) . ' contact your administrator.');
                    return redirect()->route('client.dashboard');
                }            
            }
        }
        return $next($request);
    }
}
