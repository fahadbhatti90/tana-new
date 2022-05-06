<?php

namespace App\Http\Controllers\SalesOrderedRevenue;

use App\Http\Controllers\Controller;
use App\Model\SalesOrdered\LoadSalesOrdered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoadSalesOrderController extends Controller
{
    /**
     * LoadInventoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailySalesOrder', 'loadWeeklySalesOrder', 'loadMonthlySalesOrder']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('salesOrderedRevenue.load');
    }

    /**
     * Load Daily inventory Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailySalesOrder(Request $request)
    {
        $rules = array(
            'load_daily_sale_order_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        } // end if

        $dateRange = explode(" - ", $request['load_daily_sale_order_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadSalesOrdered::loadDailySalesOrdered($startDate, $endDate);

        return response()->json([
            'success' => 'Sales ordered daily records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }

    /**
     * Load Weekly Sales ordered Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklySalesOrder(Request $request)
    {
        $rules = array(
            'load_weekly_sale_order_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_sale_order_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadSalesOrdered::loadWeeklySalesOrdered($startDate, $endDate);

        return response()->json([
            'success' => 'Sales ordered weekly records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }

    /**
     * Load Monthly Sales ordered Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlySalesOrder(Request $request)
    {
        $rules = array(
            'load_monthly_sale_order_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_sale_order_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        } // end if

        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadSalesOrdered::loadMonthlySalesOrdered($startDate, $endDate);

        return response()->json([
            'success' => 'Sales ordered monthly records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }
}
