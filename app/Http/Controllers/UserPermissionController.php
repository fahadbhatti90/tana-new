<?php

namespace App\Http\Controllers;

use App\Model\Module;
use App\Model\Permission;
use App\Model\Role;
use App\Model\RoleModulePermission;
use App\Model\User;
use App\Model\UserModule;
use App\Model\UserModulePermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class UserPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorizedUser');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $authorization = $user->authorization()->get();
        $data = array();
        foreach(UserModule::all() as $module){
            foreach(Permission::all() as $permission){
                  if($authorization->where('fk_module_id', $module->module_id)->where('fk_permission_id', $permission->permission_id)->first()){
                      $data[$module->module_name."-".$permission->permission_name] = true;
                  }else{
                      $data[$module->module_name."-".$permission->permission_name] = false;
                  }
            }
        }
        return view('user.permission')
            ->with('user',$user)
            ->with('permissions',Permission::all())
            ->with('modules',UserModule::all())
            ->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserAuthorization(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authorization = $user->authorization();
        $authorization->delete();
        foreach(UserModule::all() as $module){
            foreach(Permission::all() as $permission){
                if(isset($request['auth'][$module->module_name."-".$permission->permission_name])){
                    $form_data = array(
                        'fk_user_id' => $id,
                        'fk_permission_id' => $permission->permission_id,
                        'fk_module_id' => $module->module_id,
                    );
                    UserModulePermission::create($form_data);
                }
            }
        }
        return response()->json(['success' => 'User Authorizations are Refreshed']);
    }
}
