<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\CronJob;
use App\Model\Ams\Profile;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CornJobController extends Controller
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
            $data = CronJob::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cron_time', function ($data) {
                    return date('H:i:s', strtotime($data->cron_time));
                })
                ->addColumn('next_run', function ($data) {
                    if ($data->cron_status == 'disable') {
                        return 'Cron disabled';
                    }
                    return date('M d, Y - H:i:s', strtotime($data->next_run));
                })
                ->addColumn('status', function ($data) {
                    if ($data->cron_status == 'disable') {
                        return '<span class="badge badge-danger badge-pill mr-2 test ">Disabled</span>';
                    } else {
                        return '<span class="badge badge-info badge-pill mr-2 test ">Enabled</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = "";
                    if ($data->cron_status == 'enable') {
                        $button .= "<div class='custom-control custom-switch custom-control-inline align-middle'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->cron_id' value='disable' checked>
                                        <label class='custom-control-label' for='$data->cron_id'>
                                            <span class='switch-text-right'>off</span>
                                            <span class='switch-text-left'>on</span>
                                        </label>
                                    </div>";
                    } else {
                        $button .= "<div class='custom-control custom-switch custom-control-inline align-middle'>
                                        <input type='checkbox' class='status custom-control-input' name='status' id='$data->cron_id' value='enable'>
                                        <label class='custom-control-label' for='$data->cron_id'>
                                            <span class='switch-text-right'>off</span>
                                            <span class='switch-text-left'>on</span>
                                        </label>
                                    </div>";
                    }
                    $button .= "<button type='button' name='edit' id='" . $data->cron_id . "' title='Edit Time' class='edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light'><i class='feather icon-edit'></i> </button>";
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('ams.cron');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = CronJob::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'cron_time' => ['required'],
            'cron_name' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'cron_name' => $request['cron_name'],
            'cron_time' => $request['cron_time'],
            'next_run' => date('Y-m-d', strtotime(' +1 day')) . " " . $request['cron_time']
        );

        CronJob::where("cron_id", $id)->update($form_data);

        return response()->json(['success' => 'Schedule Information is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $rules = array(
            'cron_status' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $cron_info = CronJob::where("cron_id", $id)->get()->first();
        $next_run = $cron_info->next_run;
        if ($request['cron_status'] == 'enable') {
            $next_run = date('Y-m-d', strtotime(' +1 day')) . " " . $cron_info->cron_time;
        }

        $form_data = array(
            'cron_status' => $request['cron_status'],
            'modified_date' => date('Y-m-d H:i:s'),
            'next_run' => $next_run,
        );

        CronJob::where("cron_id", $id)->update($form_data);

        return response()->json(['success' => 'Cron status is updated']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function reportStatus()
    {
        $reportList = CronJob::orderBy('cron_type', 'DESC')->get();
        return view('ams.status')
            ->with('reportList', $reportList);
    }

    /**
     * Get Sales detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getReportSatus(Request $request)
    {
        $rules = array(
            'currentReport' => ['required'],
            'currentReportDate' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $date = date('Y-m-d', strtotime($request['currentReportDate'])); // convert String to time and set date format "Y-m-d" for date
        $profile = Profile::get();

        return response()->json([
            'profile' => $profile,
            'status' => 'Report Information table development is in progress, Table shows only profile list'
        ]);
    }

    /**
     * Get Sales detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function recoverReport(Request $request)
    {
        $rules = array(
            'report' => ['required', 'numeric'],
            'daysBack' => ['required', 'numeric', 'min:1', 'max:60'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select valid recovery filters']);
        }

        $report = CronJob::findOrFail($request['report']);

        if($report->is_running == 1){
            return response()->json(['info' => 'Data recovery is already running for this report']);
        }
        $report->recover = $request['daysBack'];
        $report->recover_back_from_date = date('Y-m-d');
        $report->is_running = 1;
        $report->save();

        return response()->json([
            'success' => 'Report data recovery is started',
        ]);
    }
    /**
     * Recover Report by range.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function recoverReportByRange(Request $request)
    {
        $rules = array(
            'recovery_range_report' => ['required', 'numeric'],
            'recovery_range_value' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select valid recovery filters']);
        }


        $dateRange = explode(" - ", $request['recovery_range_value']); // split date range on " - "
        $startTime = new DateTime($dateRange[0]);
        $endTime = new DateTime($dateRange[1]);
        $recover_back_from_date = date('Y-m-d', strtotime('+1 day', strtotime($dateRange[1])));

        $report = CronJob::findOrFail($request['recovery_range_report']);

        if($report->is_running == 1){
            return response()->json(['info' => 'Data recovery is already running for this report']);
        }
        $report->recover = $startTime->diff($endTime)->days + 1;
        $report->recover_back_from_date = $recover_back_from_date;
        $report->is_running = 1;
        $report->save();

        return response()->json([
            'success' => 'Report data recovery is started',
        ]);
    }
}
