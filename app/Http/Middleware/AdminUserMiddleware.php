<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;


class AdminUserMiddleware
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
        // if(!\Auth::guard('admin')->check()){
        //     echo 'lol';
        //     return Redirect::route('admin.login');
        // }
        if(!Auth::check()) {
            return Redirect::route('admin.login');
        }
        else
        {
            if(Auth::user()->user_type != '0')
            {
                Auth::logout();
                return Redirect::route('admin.login');
            }
        }
        return $next($request);
    }
}
