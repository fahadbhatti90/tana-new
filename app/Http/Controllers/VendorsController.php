<?php

namespace App\Http\Controllers;

use App\Model\Ams\Profile;
use App\Model\Brand;
use App\Model\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class VendorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,5')->only(['index', 'show']);
        $this->middleware('permission:2,5')->only(['store']);
        $this->middleware('permission:3,5')->only(['edit', 'update']);
        $this->middleware('permission:4,5')->only(['updateStatus', 'restore']);

        $this->middleware('permission:1,13')->only(['getAssociatedProfiles', 'getUnassignedProfiles']);
        $this->middleware('permission:2,13')->only(['assignProfile']);
        $this->middleware('permission:4,13')->only(['unAssignProfile']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserVendor();
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
                    if (checkOptionPermission(array(13), 1)) {
                        $button .= ' <a href="' . app('url')->route('user-vendors.profiles', $data->vendor_id, true) . '" title="Associated Entity" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="fa fa-list-ul"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('user-vendors.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserArchiveVendor();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(5), 4)) {
                        $button .= ' <button type="button"  name="restoreVendor" id="' . $data->vendor_id . '" title="Restore Vendor" value="1" class="restoreVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('user-vendors.restore');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'vendor_name' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_name,NULL,vendor_id,region,' . $request['region']],
            'vendor_alias' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_alias,NULL,vendor_id,region,'],
            'region' => ['required', 'string', 'max:3'],
            'marketplace' => ['required', 'string', 'max:3'],
            'tier' => ['required', 'string', 'max:255'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendor_name' => $request['vendor_name'],
            'vendor_alias' => $request['vendor_alias'],
            'region' => $request['region'],
            'tier' => $request['tier'],
            'marketplace' => $request['marketplace'],
        );

        Vendors::create($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Vendor is added successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Vendors::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'vendor_name' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_name,' . $id . ',vendor_id,region,' . $request['region']],
            'vendor_alias' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_alias,NULL,vendor_id,region,'],
            'region' => ['required', 'string', 'max:3'],
            'tier' => ['required', 'string', 'max:255'],
            'marketplace' => ['required', 'string', 'max:3'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendor_name' => $request['vendor_name'],
            'vendor_alias' => $request['vendor_alias'],
            'region' => $request['region'],
            'tier' => $request['tier'],
            'marketplace' => $request['marketplace'],
        );

        Vendors::where("vendor_id", $id)->update($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Vendor is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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

        Vendors::where("vendor_id", $id)->update($form_data);
        Brand::updateSDM(); // update data in SDM

        return response()->json(['success' => 'Vendor status is updated']);
    }

    /**
     * Display the Brand Associated Vendors.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function getAssociatedProfiles(Request $request, $id)
    {
        if ($request->ajax()) {
            $vendor = Vendors::findorfail($id);
            $data = $vendor->profile()->where('is_active', '!=', 2);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if ($data->is_active == 1) {
                        return '<span class="badge badge-success badge-pill float-right mr-2 test ">Active</span>';
                    } else {
                        return '<span class="badge badge-danger badge-pill float-right mr-2 test ">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(13), 4)) {
                        $button = '<button type="button" name="unLinkProfile" id="' . $data->id . '" title="Un-Assign Entity" class="unLinkProfile btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="fa fa-unlink"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('user-vendors.profiles')->with('vendor_id', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getUnassignedProfiles($id)
    {
        if (request()->ajax()) {
            $vendor = Vendors::find($id);
            $profile = new Profile();
            $data = $profile->getUnassignedProfiles();
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignProfile(Request $request, $id)
    {
        $length = count($request['profile_info']);
        for ($i = 0; $i < $length; $i++) {
            $profile_info = explode("-", $request['profile_info'][$i]);
            $form_data = array(
                'fk_profile_id' => $profile_info[0],
                'fk_vendor_id' => $profile_info[2],
                'entity_id' => $profile_info[1],
            );
            $profile = Profile::findorfail($request['profile_info']);
            $vendor = Vendors::findorfail($id);
            $vendor->profileAssign($form_data);
        }
        return response()->json(['success' => 'New profile is assigned']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function unAssignProfile(Request $request, int $id)
    {
        DB::table('mgmt_vendor_entity')->where('fk_profile_id', $id)->delete();
        return response()->json(['success' => 'Profile is unassigned successfully']);
    }
}
