<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\purchaseOrder\LoadPo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LoadPurchaseOrderController extends Controller
{
    /**
     * LoadPo Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyPo', 'loadWeeklyPo', 'loadMonthlyPo']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('purchaseOrder.load');
    }

    /**
     * Load Daily PO Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyPo(Request $request)
    {
        $rules = array(
            'load_daily_po_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select Date Range']);
        }

        $dateRange = explode(" - ", $request['load_daily_po_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyPoResponse = LoadPo::loadDailyPo($startDate, $endDate);

        return response()->json([
            'success' => 'PO daily records are successfully loaded',
            'response' => $dailyPoResponse
        ]);
    }

    /**
     * Load Weekly PO Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyPo(Request $request)
    {
        $rules = array(
            'load_weekly_po_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_po_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //comment by hamza younas as ahad required
        if ($error->fails() or !checkDateRange(5, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        }

        //call Model Static Function for Calling Store Procedure
        $dailyPoResponse = LoadPo::loadWeeklyPo($startDate, $endDate);

        return response()->json([
            'success' => 'PO weekly records are successfully loaded',
            'response' => $dailyPoResponse
        ]);
    }

    /**
     * Load Monthly PO Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyPo(Request $request)
    {
        $rules = array(
            'load_monthly_po_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_po_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        }

        //call Model Static Function for Calling Store Procedure
        $dailyPoResponse = LoadPo::loadMonthlyPo($startDate, $endDate);

        return response()->json([
            'success' => 'PO monthly records are successfully loaded',
            'response' => $dailyPoResponse
        ]);
    }
}
