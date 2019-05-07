<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckPermission
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (!User::hasAccess($permission)) {
            return $this->denied($request);
        }

        return $next($request);
    }

    public function denied($request)
    {
        if ($request->ajax()) {
            $message = 'Unauthorized';
            return response()->json(['error' => $message], 401);
        } else {
            $message = 'You do not have permission to do that.';
            session()->flash('error', $message);
            if (Auth::user()->user_type == 'user') {
                return redirect('/dashboard');
            } else if (Auth::user()->user_type == 'client') {
                return redirect('/client-dashboard');
            } else if (Auth::user()->user_type == 'coach') {
                return redirect('/coach-dashboard');
            }
            return redirect()->back();
        }
    }
}
