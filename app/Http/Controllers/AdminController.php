<?php

namespace App\Http\Controllers;

use App\Events\loadReport;
use App\Mail\welcomeMail;
use App\Model\Permission;
use App\Model\Profile;
use App\Model\User;
use App\Model\Role;
use App\Model\UserModule;
use App\Model\UserModulePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,2')->only(['index','show']);
        $this->middleware('permission:2,2')->only(['store']);
        $this->middleware('permission:3,2')->only(['edit','update']);
        $this->middleware('permission:4,2')->only(['updateStatus','restore']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $adminRole = Role::where('role_id','2')->first();
            $data = $adminRole->users()->where('is_active', '!=',2);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function($data){

                    if(checkOptionPermission(array(2),4)){
                        $check = "";
                        $disable = ($data->user_id == auth()->user()->user_id)?"disabled":"";
                        if($data->is_active == 1){
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->user_id' value='0' $disable  checked>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        }else{
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class='status custom-control-input' name='status' id='$data->user_id' value='1' $disable>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        }
                        return $check;
                    }else{
                        return ($data->is_active == 1)?"Active":"Inactive";
                    }

                })
                ->addColumn('action', function($data){
                    $button = "";
                    if(checkOptionPermission(array(2),3)) {
                        $button = '<button type="button" name="edit" id="' . $data->user_id . '" title="Edit Admin Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if(checkOptionPermission(array(2),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="deleteUser" id="' . $data->user_id . '" title="Delete Admin" value="2" class="deleteUser btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    $role_id = auth()->user()->roles()->get()->first()->role_id;
                    if(($role_id == 1 || $role_id == 2) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <a href="' . app('url')->route('user.authorization', $data->user_id, true) . '" title="User Permissions" class="auth btn-icon btn btn-success btn-round btn-sm waves-effect waves-light"><i class="feather icon-lock"></i> </a>';
                    }
                    return $button;
                })
                ->rawColumns(['action','is_active'])
                ->make(true);
        }
        return view('admin.index');
    }

    /**
     * Display a list of archive admin.
     *s
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function restore(Request $request)
    {
        if($request->ajax())
        {
            $adminRole = Role::where('role_id','2')->first();
            $data = $adminRole->users()->where('is_active', 2);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = "";
                    if(checkOptionPermission(array(2),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="restoreAdmin" id="' . $data->user_id . '" title="Restore Admin" value="1" class="restoreAdmin btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.restore');
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
     * @param \Illuminate\Http\Request $request
     * @param $profile
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mgmt_user'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $password = generatePassword();

        $form_data = array(
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($password),
        );

        $user = User::create($form_data);

        foreach (UserModule::all('module_id') as $module) {
            foreach (Permission::all('permission_id') as $permission) {
                $form_data = array(
                    'fk_user_id' => $user->user_id,
                    'fk_permission_id' => $permission->permission_id,
                    'fk_module_id' => $module->module_id,
                );
                UserModulePermission::create($form_data);
            }
        }

        $adminRole = Role::where('role_id','2')->first();
        $user->roles()->attach($adminRole);

        $mailContent['username'] = $user->username;
        $mailContent['email'] = $user->email;
        $mailContent['password'] = $password;
        Mail::to($mailContent['email'])->send(new welcomeMail($mailContent));

        return response()->json(['success' => 'Admin is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data , 'role' => $data->roles()->get()->first()]);
        }
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
            $data = User::findOrFail($id);
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
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique("mgmt_user")->ignore($request->hidden_id, 'user_id'), ],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'username' => $request['username'],
            'email' => $request['email'],
        );

        User::where( "user_id" ,$id)->update($form_data);

        return response()->json(['success' => 'Admin is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $rules = array(
            'is_active' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'is_active' => $request['is_active'],
        );

        User::where( "user_id" ,$id)->update($form_data);

        return response()->json(['success' => 'Admin status is updated']);
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
