<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\AmsSearchTermLoad;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AmsSearchTermLoadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailySearchTerm', 'loadWeeklySearchTerm', 'loadMonthlySearchTerm']);
    } // end function

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ams.amsVerify.keywordSearchTermLoad');
    } // end function

    /**
     * Load Daily searchTerm Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailySearchTerm(Request $request)
    {
        $rules = array(
            'load_daily_search_term_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        }

        $dateRange = explode(" - ", $request['load_daily_search_term_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailysearchTermResponse = AmsSearchTermLoad::loadDailySearchTerm($startDate, $endDate);

        return response()->json([
            'success' => 'searchTerm daily records are successfully loaded',
            'response' => $dailysearchTermResponse
        ]);
    }

    /**
     * Load Weekly searchTerm Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklySearchTerm(Request $request)
    {
        $rules = array(
            'load_weekly_search_term_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_search_term_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        }
        //call Model Static Function for Calling Store Procedure
        $dailysearchTermResponse = AmsSearchTermLoad::loadWeeklySearchTerm($startDate, $endDate);

        return response()->json([
            'success' => 'Search term weekly records are successfully loaded',
            'response' => $dailysearchTermResponse
        ]);
    }

    /**
     * Load Monthly searchTerm Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlySearchTerm(Request $request)
    {
        $rules = array(
            'load_monthly_search_term_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_search_term_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        }

        //call Model Static Function for Calling Store Procedure
        $dailySearchTermResponse = AmsSearchTermLoad::loadMonthlySearchTerm($startDate, $endDate);

        return response()->json([
            'success' => 'Search term monthly records are successfully loaded',
            'response' => $dailySearchTermResponse
        ]);
    }

    //Detail pages for Search Term sd
    public function dashboard(Request $request)
    {
        if ($request->ajax()) {

            $data = AmsSearchTermLoad::getDashboardRecord();
            return DataTables::of($data)
                ->make(true);
        }
        return view('ams.amsVerify.searchTermLoad');
    }
}
