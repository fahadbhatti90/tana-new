<?php

namespace App\Http\Controllers\Dropship;

use App\Http\Controllers\Controller;
use App\Model\Dropship\LoadDropship;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoadDropshipController extends Controller
{
    /**
     * LoadDropshipController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyDropship', 'loadWeeklyDropship', 'loadMonthlyDropship']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('dropship.load');
    }

    /**
     * Load Daily inventory Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyDropship(Request $request)
    {
        $rules = array(
            'load_daily_dropship_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        } // end if

        $dateRange = explode(" - ", $request['load_daily_dropship_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyDropshipResponse = LoadDropship::loadDailyDropship($startDate, $endDate);

        return response()->json([
            'success' => 'Dropship daily records are successfully loaded',
            'response' => $dailyDropshipResponse
        ]);
    }

    /**
     * Load Weekly Dropship Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyDropship(Request $request)
    {
        $rules = array(
            'load_weekly_dropship_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_dropship_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $dailyDropshipResponse = LoadDropship::loadWeeklyDropship($startDate, $endDate);

        return response()->json([
            'success' => 'Dropship weekly records are successfully loaded',
            'response' => $dailyDropshipResponse
        ]);
    }

    /**
     * Load Monthly Dropship Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyDropship(Request $request)
    {
        $rules = array(
            'load_monthly_dropship_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_dropship_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        } // end if

        //call Model Static Function for Calling Store Procedure
        $dailyDropshipResponse = LoadDropship::loadMonthlyDropship($startDate, $endDate);

        return response()->json([
            'success' => 'Dropship monthly records are successfully loaded',
            'response' => $dailyDropshipResponse
        ]);
    }
}
