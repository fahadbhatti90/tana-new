<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Ams\Amslogs;
use App\Model\Ams\AmsVerify;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Session;
use Helper;
use Illuminate\Http\JsonResponse;
use App\Mail\errorMail;
use Illuminate\Support\Facades\Mail;

class AmsVerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AmsVerify::spAmsVerify('campaing', 'SP', '0', 'Main');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailverifyCampaign', $data->Vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeCampaignSpVendor"  id="' . $data->Vendor_id . '" title="Delete Records" class="removeCampaignSpVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignVerify');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function verifySb(Request $request)
    {
        if ($request->ajax()) {
            $data = AmsVerify::spAmsVerify('campaing', 'SB', '0', 'Main');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailverifyCampaignSb', $data->Vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeCampaignSbVendor"  id="' . $data->Vendor_id . '" title="Delete Records" class="removeCampaignSbVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignVerify');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function verifySd(Request $request)
    {
        if ($request->ajax()) {
            $data = AmsVerify::spAmsVerify('campaing', 'SD', '0', 'Main');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailverifyCampaignSd', $data->Vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeCampaignSdVendor"  id="' . $data->Vendor_id . '" title="Delete Records" class="removeCampaignSdVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignVerify');
    }
    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSp()
    {
        AmsVerify::spDeleteDuplication('campaing', 'SP');
        return response()->json(['success' => 'Sponsor Product duplicate record(s) deleted successfully']);
    }
    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSb()
    {
        AmsVerify::spDeleteDuplication('campaing', 'SB');
        return response()->json(['success' => 'Sponsor Brand duplicate record(s) deleted successfully']);
    }
    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSd()
    {
        AmsVerify::spDeleteDuplication('campaing', 'SD');
        return response()->json(['success' => 'Sponsor Display duplicate record(s) deleted successfully']);
    }

    public function moveAllToCore()
    {
        $checkResult = AmsVerify::moveToCore();
        return response()->json(['success' => 'Data Moved successfully']);
    }
    //Detail pages for campaign sp
    public function DetailverifySp(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = AmsVerify::spAmsVerify('campaing', 'SP', $id, 'Detail');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendorSp"  id="' . $data->Reprted_Date . '" title="Delete Record" class="removeVendorSp btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignDetailVerifySp')->with('vendor_id', $id);
    }
    //Detail pages for campaign sb
    public function DetailverifySb(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = AmsVerify::spAmsVerify('campaing', 'SB', $id, 'Detail');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendorSb"  id="' . $data->Reprted_Date . '" title="Delete Record" class="removeVendorSb btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignDetailVerifySb')->with('vendor_id', $id);
    }
    //Detail pages for campaign sd
    public function DetailverifySd(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = AmsVerify::spAmsVerify('campaing', 'SD', $id, 'Detail');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendorSd"  id="' . $data->Reprted_Date . '" title="Delete Record" class="removeVendorSd btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('ams.amsVerify.campaignDetailVerifySd')->with('vendor_id', $id);
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCampaignSp($id)
    {
        AmsVerify::deleteCampaignSpRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCampaignSb($id)
    {
        AmsVerify::deleteCampaignSbRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCampaignSd($id)
    {
        AmsVerify::deleteCampaignSdRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyCampaipaignSpByDate(Request $request, $id)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        AmsVerify::deleteSelectedRecordSp($id, $request['received_date']);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyCampaipaignSbByDate(Request $request, $id)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        AmsVerify::deleteSelectedRecordSb($id, $request['received_date']);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyCampaipaignSdByDate(Request $request, $id)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        AmsVerify::deleteSelectedRecordSd($id, $request['received_date']);
        return response()->json(['success' => 'Record deleted successfully']);
    }
}
