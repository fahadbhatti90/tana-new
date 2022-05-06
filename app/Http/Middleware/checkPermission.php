<?php

namespace App\Http\Middleware;

use App\Model\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class checkPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission_id, $module_id)
    {
        $roles = Role::findOrFail(Auth::user()->roles()->get()->first()->role_id);
        $authorization = $roles->authorization()->get();
        if($authorization->where('fk_module_id',$module_id)->where('fk_permission_id', $permission_id)->first()){
            return $next($request);
        }
        return redirect('home');
    }
}
