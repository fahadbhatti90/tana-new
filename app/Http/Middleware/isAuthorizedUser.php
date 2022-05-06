<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isAuthorizedUser
{
    /**
     * Handle an incoming request.
     * Middleware is for User based authorization permission
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->roles()->get()->first()->role_id == 1 || Auth::user()->roles()->get()->first()->role_id == 2){
            return $next($request);
        }
        return redirect('home');
    }
}
