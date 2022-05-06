<?php

namespace App\Http\Controllers\Traffic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Traffic\LoadTraffic;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoadTrafficController extends Controller
{
    /**
     * LoadInventoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyTraffic', 'loadWeeklyTraffic', 'loadMonthlyTraffic']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('traffic.load');
    }

    /**
     * Load Traffic Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyTraffic(Request $request)
    {
        $rules = array(
            'load_daily_traffic_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        } // end if

        $dateRange = explode(" - ", $request['load_daily_traffic_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $TrafficResponse = LoadTraffic::loadDailyTraffic($startDate, $endDate);

        return response()->json([
            'success' => 'Traffic daily records are successfully loaded',
            'response' => $TrafficResponse
        ]);
    }

    /**
     * Load Weekly Traffic Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyTraffic(Request $request)
    {
        $rules = array(
            'load_weekly_traffic_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_traffic_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $TrafficResponse = LoadTraffic::loadWeeklyTraffic($startDate, $endDate);

        return response()->json([
            'success' => 'Traffic weekly records are successfully loaded',
            'response' => $TrafficResponse
        ]);
    }

    /**
     * Load Monthly Traffic Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyTraffic(Request $request)
    {
        $rules = array(
            'load_monthly_traffic_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_traffic_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        } // end if

        //call Model Static Function for Calling Store Procedure
        $TrafficResponse = LoadTraffic::loadMonthlyTraffic($startDate, $endDate);

        return response()->json([
            'success' => 'Traffic monthly records are successfully loaded',
            'response' => $TrafficResponse
        ]);
    }
}
