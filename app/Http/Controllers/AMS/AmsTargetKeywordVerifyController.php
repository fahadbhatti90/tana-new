<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\AmsKeywordTargetCampaignVerify;
use Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Session;
use Yajra\DataTables\DataTables;

class AmsTargetKeywordVerifyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        return view('ams.amsVerify.keywordTargetVerify');
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
        $rules = array(
            'date_range' => ['required'],
            'reported_type' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        if (isset($_COOKIE["check"])) {
            if ($_COOKIE["cookie"] == 0) {
                $request['reported_type'] = $_COOKIE["type"];
                $request['date_range'] = $_COOKIE["date"];
                setcookie("cookie", "1", time() + 86400, "/");
                if ($_COOKIE["pageCheck"] == 'sb') {
                    setcookie("pageCheck", "sb", time() + 86400, "/");
                }
            } elseif ($_COOKIE["cookie"] == 1) {
                if ($request['date_range'] == $request['checkDate']) {
                    $request['reported_type'] = $_COOKIE["type"];
                    $request['date_range'] = $_COOKIE["date"];
                    setcookie("cookie", "1", time() + 86400, "/");
                }
            }
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date['id' => $sb_data->Profile_id, 'name' => $sb_data->Domian]
        if ($request->ajax()) {
            $sb_data = AmsKeywordTargetCampaignVerify::getVerifyRecord($request['reported_type'], 'SB', $startDate, $endDate);
            return DataTables::of($sb_data)
                ->addColumn('action', function ($sb_data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailAmsVerifySb', ['id' => $sb_data->Profile_id, 'type' => $sb_data->type, 'start_dt' => $sb_data->start_dt, 'end_dt' => $sb_data->end_dt], true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeAmsSbVendor"  id="' . $sb_data->Profile_id . '" title="Delete Records" class="removeAmsSbVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.keywordTargetVerify');
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
        $rules = array(
            'date_range' => ['required'],
            'reported_type' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        if (isset($_COOKIE["check"])) {
            if ($_COOKIE["cookie"] == 0) {
                $request['reported_type'] = $_COOKIE["type"];
                $request['date_range'] = $_COOKIE["date"];
                setcookie("cookie", "1", time() + 86400, "/");
                if ($_COOKIE["pageCheck"] == 'sd') {
                    setcookie("pageCheck", "sd", time() + 86400, "/");
                }
            } elseif ($_COOKIE["cookie"] == 1) {
                if ($request['date_range'] == $request['checkDate']) {
                    $request['reported_type'] = $_COOKIE["type"];
                    $request['date_range'] = $_COOKIE["date"];
                    setcookie("cookie", "1", time() + 86400, "/");
                }
            }
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        if ($request->ajax()) {
            $sd_data = AmsKeywordTargetCampaignVerify::getVerifyRecord($request['reported_type'], 'SD', $startDate, $endDate);
            return DataTables::of($sd_data)
                ->addColumn('action', function ($sd_data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailAmsVerifySd', ['id' => $sd_data->Profile_id, 'type' => $sd_data->type, 'start_dt' => $sd_data->start_dt, 'end_dt' => $sd_data->end_dt], true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeAmsSdVendor"  id="' . $sd_data->Profile_id . '" title="Delete Records" class="removeAmsSdVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.keywordTargetVerify');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getReportsData(Request $request)
    {
        $rules = array(
            'date_range' => ['required'],
            'reported_type' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        if (isset($_COOKIE["check"])) {
            if ($_COOKIE["cookie"] == 0) {
                $request['reported_type'] = $_COOKIE["type"];
                $request['date_range'] = $_COOKIE["date"];
                setcookie("cookie", "1", time() + 86400, "/");
            } elseif ($_COOKIE["cookie"] == 1) {
                if ($request['date_range'] == $request['checkDate']) {
                    $request['reported_type'] = $_COOKIE["type"];
                    $request['date_range'] = $_COOKIE["date"];
                    setcookie("cookie", "1", time() + 86400, "/");
                }
            }
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        if ($request->ajax()) {
            $sp_data = AmsKeywordTargetCampaignVerify::getVerifyRecord($request['reported_type'], 'SP', $startDate, $endDate);
            return DataTables::of($sp_data)
                ->addColumn('action', function ($sp_data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('ams.DetailAmsVerify', ['id' => $sp_data->Profile_id, 'type' => $sp_data->type, 'start_dt' => $sp_data->start_dt, 'end_dt' => $sp_data->end_dt,], true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeAmsSp"  id="' . $sp_data->Profile_id . '" title="Delete Records" class="removeAmsSp btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('ams.amsVerify.keywordTargetVerify');
    }

    //Detail pages for campaign sp
    public function DetailverifySp(Request $request, $id, $type, $start, $end)
    {
        $start_dt = str_replace("-", "/", $start);
        $end_dt = str_replace("-", "/", $end);
        $res = $start_dt . " - " . $end_dt;

        setcookie("check", "yes", time() + 86400, "/");
        setcookie("type", $type, time() + 86400, "/");
        setcookie("date", $res, time() + 86400, "/");
        setcookie("cookie", '0', time() + 86400, "/");
        setcookie("pageCheck", "sp", time() + 86400, "/");
        if ($request->ajax()) {
            $data = AmsKeywordTargetCampaignVerify::getDetailVerifyRecord($type, 'SP', $start, $end, $id);

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
        return view('ams.amsVerify.AmsDetailVerify.amsDetailVerifySp')->with('vendor_id', $id)->with('type', $type)->with('start', $start)->with('end', $end);
    }

    //Detail pages for campaign sb
    public function DetailverifySb(Request $request, $id, $type, $start, $end)
    {
        $start_dt = str_replace("-", "/", $start);
        $end_dt = str_replace("-", "/", $end);
        $res = $start_dt . " - " . $end_dt;

        setcookie("check", "yes", time() + 86400, "/");
        setcookie("type", $type, time() + 86400, "/");
        setcookie("date", $res, time() + 86400, "/");
        setcookie("cookie", '0', time() + 86400, "/");
        setcookie("pageCheck", "sb", time() + 86400, "/");
        if ($request->ajax()) {

            $data = AmsKeywordTargetCampaignVerify::getDetailVerifyRecord($type, 'SB', $start, $end, $id);
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
        return view('ams.amsVerify.AmsDetailVerify.amsDetailVerifySb')->with('vendor_id', $id)->with('type', $type)->with('start', $start)->with('end', $end);
    }

    //Detail pages for campaign sd
    public function DetailverifySd(Request $request, $id, $type, $start, $end)
    {
        $start_dt = str_replace("-", "/", $start);
        $end_dt = str_replace("-", "/", $end);
        $res = $start_dt . " - " . $end_dt;

        setcookie("check", "yes", time() + 86400, "/");
        setcookie("type", $type, time() + 86400, "/");
        setcookie("date", $res, time() + 86400, "/");
        setcookie("cookie", '0', time() + 86400, "/");
        setcookie("pageCheck", "sd", time() + 86400, "/");
        if ($request->ajax()) {
            $data = AmsKeywordTargetCampaignVerify::getDetailVerifyRecord($type, 'SD', $start, $end, $id);

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
        return view('ams.amsVerify.AmsDetailVerify.amsDetailVerifySd')->with('vendor_id', $id)->with('type', $type)->with('start', $start)->with('end', $end);
    }

    //Detail pages for campaign sd
    public function Dashboard(Request $request)
    {
        if ($request->ajax()) {

            $data = AmsKeywordTargetCampaignVerify::getDashboardRecord($request['reported_type']);
            return DataTables::of($data)
                ->make(true);
        }
        return view('ams.amsVerify.keywordTargetVerify');
    }

    //To move data in core
    public function moveAllToCore(Request $request)
    {
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        $checkResult = AmsKeywordTargetCampaignVerify::moveToCore($request['reported_type'], $startDate, $endDate);
        return response()->json(['success' => 'Data moved successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAmsSp(Request $request, $id)
    {
        AmsKeywordTargetCampaignVerify::deleteSpRecord($request['reported_type'], $id);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAmsSb(Request $request, $id)
    {
        AmsKeywordTargetCampaignVerify::deleteSbRecord($request['reported_type'], $id);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAmsSd(Request $request, $id)
    {
        AmsKeywordTargetCampaignVerify::deleteSdRecord($request['reported_type'], $id);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyAmsSpByDate(Request $request, $id, $type)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        AmsKeywordTargetCampaignVerify::deleteSelectedRecordSp($id, $request['received_date'], $type);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyAmsSbByDate(Request $request, $id, $type)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        AmsKeywordTargetCampaignVerify::deleteSelectedRecordSb($id, $request['received_date'], $type);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyAmsSdByDate(Request $request, $id, $type)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        AmsKeywordTargetCampaignVerify::deleteSelectedRecordSd($id, $request['received_date'], $type);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSp(Request $request)
    {
        AmsKeywordTargetCampaignVerify::spDeleteDuplication($request['reported_type'], 'SP');
        return response()->json(['success' => 'Sponsor product duplicate record(s) deleted successfully']);
    }

    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSb(Request $request)
    {
        AmsKeywordTargetCampaignVerify::spDeleteDuplication($request['reported_type'], 'SB');
        return response()->json(['success' => 'Sponsor brand duplicate record(s) deleted successfully']);
    }

    // @return \Illuminate\Http\JsonResponse
    public function deleteDuplicationSd(Request $request)
    {
        AmsKeywordTargetCampaignVerify::spDeleteDuplication($request['reported_type'], 'SD');
        return response()->json(['success' => 'Sponsor display duplicate record(s) deleted successfully']);
    }

    /**
     * Generate log table.
     *
     * @return JsonResponse
     */
    public function generateLogTable(Request $request)
    {
        $rules = array(
            'generate_log_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $response = AmsKeywordTargetCampaignVerify::generateLogTable($request['generate_log_range']);

        return response()->json([
            'success' => 'Log is generated successfully',
            'response' => $response,
        ]);
    } // end function
}
