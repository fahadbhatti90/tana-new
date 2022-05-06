<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Model\Sales\LoadSales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoadSalesController extends Controller
{
    /**
     * LoadSalesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailySales', 'loadWeeklySales', 'loadMonthlySales']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('sales.load');
    }

    /**
     * Load Daily Sales Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailySales(Request $request)
    {
        $rules = array(
            'load_daily_sales_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select Date Range']);
        } // end if

        $dateRange = explode(" - ", $request['load_daily_sales_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailySalesResponse = LoadSales::loadDailySales($startDate, $endDate);

        return response()->json([
            'success' => 'Sales daily records are successfully loaded',
            'response' => $dailySalesResponse
        ]);
    }

    /**
     * Load Weekly Sales Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklySales(Request $request)
    {
        $rules = array(
            'load_weekly_sales_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_sales_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $dailySalesResponse = LoadSales::loadWeeklySales($startDate, $endDate);

        return response()->json([
            'success' => 'Sales weekly records are successfully loaded',
            'response' => $dailySalesResponse
        ]);
    }

    /**
     * Load Weekly Sales Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlySales(Request $request)
    {
        $rules = array(
            'load_monthly_sales_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_sales_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        } // end if

        //call Model Static Function for Calling Store Procedure
        $dailySalesResponse = LoadSales::loadMonthlySales($startDate, $endDate);

        return response()->json([
            'success' => 'Sales monthly records are successfully loaded',
            'response' => $dailySalesResponse
        ]);
    }
}
