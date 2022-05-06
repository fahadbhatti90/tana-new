<?php

namespace App\Http\Middleware;

use App\Model\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class checkUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $permission_id
     * @param $module_id
     * @return mixed
     */
    public function handle($request, Closure $next, $permission_id, $module_id)
    {
        $user = User::findOrFail(Auth::user()->user_id);
        $authorization = $user->authorization()->get();
        if($authorization->where('fk_module_id',$module_id)->where('fk_permission_id', $permission_id)->first()){
            return $next($request);
        }
        return redirect('home');
    }
}
