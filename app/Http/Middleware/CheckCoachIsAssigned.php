<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Client;
use Flash;

class CheckCoachIsAssigned
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
        //check that client has coach or not.
        $client = Client::where('user_id', Auth::id())->first();
        if (!empty($client)) {
            if (!$client->coach_id) {
                Flash::warning('No coach assigned to you. Please contact your administrator for the coach.');
                // return redirect()->route('client.dashboard');
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
