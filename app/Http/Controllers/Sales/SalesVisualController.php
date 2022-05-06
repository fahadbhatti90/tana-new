<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Model\Alerts\Alerts;
use App\Model\Brand;
use App\Model\DimVendor;
use App\Model\Sales\SalesReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesVisualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userPermission:1,3')->only(['index', 'getSaleGraph']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        if (Session()->get('brand_id') != 0) {
            $vendors = Brand::findorFail(Session()->get('brand_id'))->vendors()->where('is_active', 1)->pluck('vendor_id')->all();
            $dimVendors = DimVendor::whereIN('rdm_vendor_id', $vendors)->where('marketplace', '!=', '3P')->get();
            return view('sales.visual')
                ->with('vendors', $dimVendors);
        } else {
            return view('sales.visual')
                ->with('vendors', array());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSaleGraph(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $brand = Session()->get('brand_id');

        //call Model Static Function for Calling Store Procedure
        $saleSummary = SalesReport::saleViewSummary($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleGraph = SalesReport::saleViewGraph($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinDecrease =  SalesReport::saleTopAsinDecrease($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinIncrease =  SalesReport::saleTopAsinIncrease($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinShippedCogs =  SalesReport::salesTopAsinShippedCOGS($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleCategory = SalesReport::saleViewCategory($request['range'], $brand, $request['vendor'], $startDate, $endDate);

        $range = "";
        switch ($request['range']) {
            case 1:
                //for daily report
                $range = "daily";
                break;
            case 2:
                //daily report in case of weekly
                $range = "daily";
                break;
            case 3:
                //weekly report in case of monthly
                $range = "weekly";
                break;
            case 4:
                //monthly report in case of yearly
                $range = "monthly";
                break;
        }

        $fk_user_id = Auth::user()->user_id;
        $saleSummaryAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'sale', $range, 'summary', $startDate, $endDate);
        $saleGraphAlerts = $saleSummaryAlerts;
        $saleTopAsinDecreaseAlerts =  Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'sale', $range, 'saleTopAsinDecrease', $startDate, $endDate);
        $saleTopAsinIncreaseAlerts =  Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'sale', $range, 'saleTopAsinIncrease', $startDate, $endDate);
        $saleTopAsinShippedCogsAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'sale', $range, 'saleTopAsinShippedCogs', $startDate, $endDate);
        $saleCategoryAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'sale', $range, 'category', $startDate, $endDate);

        return response()->json([
            'saleSummary' => $saleSummary,
            'saleGraph' => $saleGraph,
            'saleTopAsinDecrease' => $saleTopAsinDecrease,
            'saleTopAsinIncrease' => $saleTopAsinIncrease,
            'saleTopAsinShippedCogs' => $saleTopAsinShippedCogs,
            'saleCategory' => $saleCategory,
            'saleSummaryAlerts' => $saleSummaryAlerts,
            'saleGraphAlerts' => $saleGraphAlerts,
            'saleTopAsinDecreaseAlerts' => $saleTopAsinDecreaseAlerts,
            'saleTopAsinIncreaseAlerts' => $saleTopAsinIncreaseAlerts,
            'saleTopAsinShippedCogsAlerts' => $saleTopAsinShippedCogsAlerts,
            'saleCategoryAlerts' => $saleCategoryAlerts,
        ]);
    }
}
