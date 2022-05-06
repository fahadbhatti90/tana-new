<?php

namespace App\Http\Controllers;

use App\Model\Module;
use App\Model\Permission;
use App\Model\Role;
use App\Model\RoleModulePermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Role::all();
            $GLOBALS['sno'] = 1;
            return DataTables::of($data)
                ->addColumn('sno', function () {
                    return $GLOBALS['sno']++;
                })
                ->addColumn('action', function($data){
                    $button = ' <button type="button" name="edit" id="'.$data->role_id.'" title="Edit Role Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    $button .=' <a href="'.app('url')->route('role.show', $data->role_id, true).'" title="Role Permissions" class="auth btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-lock"></i> </a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'role_name' => ['required', 'string', 'max:255'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'role_name' => $request['role_name'],
        );

        Role::create($form_data);

        return response()->json(['success' => 'Role is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roles = Role::findOrFail($id);
        $authorization = $roles->authorization()->get();
        $data = array();
        foreach(Module::all() as $module){
            foreach(Permission::all() as $permission){
                  if($authorization->where('fk_module_id', $module->module_id)->where('fk_permission_id', $permission->permission_id)->first()){
                      $data[$module->module_name."-".$permission->permission_name] = true;
                  }else{
                      $data[$module->module_name."-".$permission->permission_name] = false;
                  }
            }
        }
        return view('role.show')
            ->with('role',$roles)
            ->with('permissions',Permission::all())
            ->with('modules',Module::all())
            ->with('data',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Role::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'role_name' => ['required', 'string', 'max:255'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'role_name' => $request['role_name'],
        );

        Role::where( "role_id" ,$id)->update($form_data);
        return response()->json(['success' => 'Role is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAuthorization(Request $request, $id)
    {
        $roles = Role::findOrFail($id);
        $authorization = $roles->authorization();
        $authorization->delete();

        foreach(Module::all() as $module){
            foreach(Permission::all() as $permission){
                if(isset($request['auth'][$module->module_name."-".$permission->permission_name])){
                    $form_data = array(
                        'fk_role_id' => $id,
                        'fk_permission_id' => $permission->permission_id,
                        'fk_module_id' => $module->module_id,
                    );
                    RoleModulePermission::create($form_data);
                }
            }
        }
        return response()->json(['success' => 'Role Authorizations are Refreshed']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
