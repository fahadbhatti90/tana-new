<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Model\Inventory\LoadInventory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoadInventoryController extends Controller
{
    /**
     * LoadInventoryController constructor.
     */
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
        return view('inventory.load');
    }

    /**
     * Load Daily inventory Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyInventory(Request $request)
    {
        $rules = array(
            'load_daily_inventory_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        } // end if

        $dateRange = explode(" - ", $request['load_daily_inventory_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadInventory::loadDailyInventory($startDate, $endDate);

        return response()->json([
            'success' => 'Inventory daily records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }

    /**
     * Load Weekly Inventory Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyInventory(Request $request)
    {
        $rules = array(
            'load_weekly_inventory_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_inventory_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadInventory::loadWeeklyInventory($startDate, $endDate);

        return response()->json([
            'success' => 'Inventory weekly records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }

    /**
     * Load Monthly Inventory Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyInventory(Request $request)
    {
        $rules = array(
            'load_monthly_inventory_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_inventory_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        } // end if

        //call Model Static Function for Calling Store Procedure
        $dailyInventoryResponse = LoadInventory::loadMonthlyInventory($startDate, $endDate);

        return response()->json([
            'success' => 'Inventory monthly records are successfully loaded',
            'response' => $dailyInventoryResponse
        ]);
    }
}
