<?php

namespace App\Http\Controllers;

use App\Model\Brand;
use App\Model\Role;
use App\Model\User;
use App\Model\Vendors;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,4')->only(['index', 'show']);
        $this->middleware('permission:2,4')->only(['store']);
        $this->middleware('permission:3,4')->only(['edit', 'update']);
        $this->middleware('permission:4,4')->only(['updateStatus', 'restore']);

        $this->middleware('permission:1,6')->only(['getAssignedUsers']);
        $this->middleware('permission:2,6')->only(['getUnassignedUsers', 'assignUser']);
        $this->middleware('permission:4,6')->only(['unassignUser']);

        $this->middleware('permission:1,7')->only(['getAssociatedVendors']);
        $this->middleware('permission:2,7')->only(['getUnassignedVendors', 'assignVendor']);
        $this->middleware('permission:4,7')->only(['unassignVendor']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserBrand();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($data) {
                    if (checkOptionPermission(array(4), 4)) {
                        $check = "";
                        if ($data->is_active == 1) {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->brand_id' value='0' checked>
                                        <label class='custom-control-label' for='$data->brand_id'>
                                        </label>
                                    </div>";
                        } else {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class='status custom-control-input' name='status' id='$data->brand_id'  value='1'>
                                        <label class='custom-control-label' for='$data->brand_id'>
                                        </label>
                                    </div>";
                        }
                        return $check;
                    } else {
                        return ($data->is_active == 1) ? "Active" : "Inactive";
                    }
                })
                ->addColumn('action', function ($data) {

                    $button = "";
                    if (checkOptionPermission(array(4), 3)) {
                        $button = '<button type="button" name="edit" id="' . $data->brand_id . '" title="Edit Brand Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i></button>';
                    }
                    if (checkOptionPermission(array(4), 4)) {
                        $button .= ' <button type="button"  name="deleteBrand" id="' . $data->brand_id . '" title="Delete Brand" value="2" class="deleteBrand btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    if (checkOptionPermission(array(6), 1)) {
                        $button .= ' <a href="' . app('url')->route('brand.users', $data->brand_id, true) . '" title="Associated Users" class="auth btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-users"></i></a>';
                    }
                    if (checkOptionPermission(array(7), 1)) {
                        $button .= ' <a href="' . app('url')->route('brand.vendors', $data->brand_id, true) . '" title="Associated Vendors" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="fa fa-list-ul"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('brand.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserArchiveBrand();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(4), 4)) {
                        $button .= ' <button type="button"  name="restoreBrand" id="' . $data->brand_id . '" title="Restore Brand" value="1" class="restoreBrand btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('brand.restore');
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
            'brand_name' => ['required', 'string', 'max:255', 'unique:mgmt_brand'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'brand_name' => $request['brand_name'],
        );

        Brand::create($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Brand is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @throws \Exception
     */
    public function show($id)
    {
    }

    /**
     * Display the Brand Associated Vendors.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function getAssociatedVendors(Request $request, $id)
    {

        if ($request->ajax()) {
            $brand = Brand::findorfail($id);
            $data = $brand->vendors()->where('is_active', '!=', 2);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($data) {
                    if (checkOptionPermission(array(5), 4)) {
                        $check = "";
                        if ($data->is_active == 1) {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                    <input type='checkbox' class=' status custom-control-input' name='status' id='$data->vendor_id' value='0' checked>
                                    <label class='custom-control-label' for='$data->vendor_id'>
                                    </label>
                                </div>";
                        } else {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                    <input type='checkbox' class='status custom-control-input' name='status' id='$data->vendor_id'  value='1'>
                                    <label class='custom-control-label' for='$data->vendor_id'>
                                    </label>
                                </div>";
                        }
                        return $check;
                    } else {
                        return ($data->is_active == 1) ? "Active" : "Inactive";
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(5), 3)) {
                        $button = '<button type="button" name="edit" id="' . $data->vendor_id . '" title="Edit Vendor Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if (checkOptionPermission(array(5), 4)) {
                        $button .= ' <button type="button"  name="deleteVendor" id="' . $data->vendor_id . '" title="Delete Vendor" value="2" class="deleteVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    if (checkOptionPermission(array(7), 4)) {
                        $button .= ' <button type="button" name="removeVendor" id="' . $data->vendor_id . '" title="Un-Assign Vendor" class="removeVendor btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-x"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('brand.vendors')->with('brand_id', $id);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Brand::findOrFail($id);
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
            'brand_name' => ['required', 'string', 'max:255', Rule::unique("mgmt_brand")->ignore($id, 'brand_id')],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'brand_name' => $request['brand_name'],
        );

        Brand::where("brand_id", $id)->update($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Brand is successfully updated']);
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

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'is_active' => $request['is_active'],
        );

        Brand::where("brand_id", $id)->update($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Brand status is updated']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUnassignedUsers($id)
    {
        if (request()->ajax()) {
            $userRole = Role::where('role_id', '3')->first();
            $brand = Brand::find($id);
            $user_id = $brand->users()->pluck('user_id')->all();
            $data = $userRole->users()->whereNotIn('user_id', $user_id)->where('is_active', 1)->orderBy('username', 'ASC')->get();
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUnassignedVendors($id)
    {
        if (request()->ajax()) {
            $brand = Brand::find($id);
            $vendors_id = $brand->vendors()->pluck('vendor_id')->all();
            $data = Vendors::whereNotIn('vendor_id', $vendors_id)->where('is_active', 1)->orderBy('vendor_name', 'ASC')->get();
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param $permission
     * @return \Illuminate\Http\Response
     */
    public function getAssignedUsers(Request $request, $id)
    {
        if ($request->ajax()) {
            $brand = Brand::findorfail($id);
            $data = $brand->users()->where('is_active', '!=', 2);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($data) {
                    if (checkOptionPermission(array(3), 4)) {
                        $disable = ($data->user_id == auth()->user()->user_id) ? "disabled" : "";
                        if ($data->is_active == 1) {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->user_id' value='0' $disable checked>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        } else {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class='status custom-control-input' name='status' id='$data->user_id' value='1' $disable>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        }
                        return $check;
                    } else {
                        return ($data->is_active == 1) ? "Active" : "Inactive";
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(3), 3)) {
                        $button = '<button type="button" name="edit" id="' . $data->user_id . '" title="Edit User Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if (checkOptionPermission(array(3), 4)) {
                        $button .= ' <button type="button"  name="deleteUser" id="' . $data->user_id . '" title="Delete User" value="2" class="deleteUser btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    if (checkOptionPermission(array(6), 4)) {
                        $button .= ' <button type="button" name="removeUser" id="' . $data->user_id . '" title="Un-Assign User" class="removeUser btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-user-x"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('brand.users')->with('brand_id', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignUser(Request $request, $id)
    {
        $user = User::findorfail($request['user_info']);
        $brand = Brand::findorfail($id);
        $brand->users()->attach($user);
        return response()->json(['success' => 'New user(s) assigned']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignVendor(Request $request, $id)
    {
        $vendor = Vendors::findorfail($request['vendor_info']);
        $brand = Brand::findorfail($id);
        $brand->vendors()->attach($vendor);
        $brand->updateSDM(); // update data in SDM
        return response()->json(['success' => 'New vendor(s) assigned']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unassignUser(Request $request, $id)
    {
        $user = User::findorfail($id);
        $brand = Brand::findorfail($request['brand_info']);
        $brand->users()->detach($user);
        return response()->json(['success' => 'User is unassigned']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unassignVendor(Request $request, $id)
    {
        $vendor = Vendors::findorfail($id);
        $brand = Brand::findorfail($request['brand_info']);
        $brand->vendors()->detach($vendor);
        $brand->updateSDM(); // update data in SDM
        return response()->json(['success' => 'Vendor is unassigned']);
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
