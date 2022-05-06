<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\AmsCampaignLoad;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AmsCampaignLoadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyInventory', 'loadWeeklyInventory', 'loadMonthlyInventory']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ams.amsVerify.campaignLoad');
    }

    /**
     * Load Daily Campaign Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyCampaing(Request $request)
    {
        $rules = array(
            'load_daily_campaing_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        }

        $dateRange = explode(" - ", $request['load_daily_campaing_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyCampaignResponse = AmsCampaignLoad::loadDailyCampaing($startDate, $endDate);

        return response()->json([
            'success' => 'Campaign daily records are successfully loaded',
            'response' => $dailyCampaignResponse
        ]);
    }

    /**
     * Load Weekly Campaign Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyCampaing(Request $request)
    {
        $rules = array(
            'load_weekly_campaing_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_campaing_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        }
        //call Model Static Function for Calling Store Procedure
        $dailyCampaignResponse = AmsCampaignLoad::loadWeeklyCampaing($startDate, $endDate);

        return response()->json([
            'success' => 'Campaign weekly records are successfully loaded',
            'response' => $dailyCampaignResponse
        ]);
    }

    /**
     * Load Monthly Campaign Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyCampaing(Request $request)
    {
        $rules = array(
            'load_monthly_campaing_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_campaing_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        }

        //call Model Static Function for Calling Store Procedure
        $dailyCampaignResponse = AmsCampaignLoad::loadMonthlyCampaing($startDate, $endDate);

        return response()->json([
            'success' => 'Campaign monthly records are successfully loaded',
            'response' => $dailyCampaignResponse
        ]);
    }

    //Detail pages for campaign sd
    public function Dashboard(Request $request)
    {
        if ($request->ajax()) {

            $data = AmsCampaignLoad::getDashboardRecord();
            return DataTables::of($data)
                ->make(true);
        }
        return view('ams.amsVerify.campaignLoad');
    }
}
