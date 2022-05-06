<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Model\Alerts\Alerts;
use App\Model\Brand;
use App\Model\DimVendor;
use App\Model\Sales\NewSalesReport;
use App\Model\Sales\SalesReport;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class NewSalesVisualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userPermission:1,3')->only(['index', 'getSales', 'getSubcategory', 'getSubcategoryShippedCOGS', 'getSubcategoryNetReceipts', 'getSubcategoryPoConfirmedRate', 'getSIPSubcategoryValue']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index()
    {
        if (Session()->get('brand_id') != 0) {
            $data = auth()->user()->getUserSelectedBrandVendor()->where('is_active', 1)->pluck('vendor_id')->all();
            $GoldVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('marketplace', '!=', '3P')->where('tier', 'Gold')->get();
            $PlatinumVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('marketplace', '!=', '3P')->where('tier', 'Platinum')->get();
            $SilverVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('marketplace', '!=', '3P')->where('tier', 'Silver')->get();
            return view('sales.newVisual')
                ->with('goldVendors', $GoldVendors)
                ->with('platinumVendors', $PlatinumVendors)
                ->with('silverVendors', $SilverVendors);
        } else {
            return view('sales.newVisual')
                ->with('goldVendors', array())
                ->with('platinumVendors', array())
                ->with('silverVendors', array());
        }
    }

    /**
     * Get Sales detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSales(Request $request)
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
        $shippedCogsGauge = NewSalesReport::shippedCogsGauge($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $shippedCogsGaugeTrailing = NewSalesReport::shippedCogsGaugeTrailing($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $netReceiptsGauge =  NewSalesReport::netReceiptsGauge($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $netReceiptsGaugeTrailing =  NewSalesReport::netReceiptsGaugeTrailing($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $poPlanGauge =  NewSalesReport::poPlanGauge($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $poPlanGaugeTrailing = NewSalesReport::poPlanGaugeTrailing($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $yoyGrowthChart = NewSalesReport::yoyGrowthChart($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $shippedCogsByGranularityChart = NewSalesReport::shippedCogsByGranularityChart($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinDecrease =  NewSalesReport::saleTopAsinDecrease($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinIncrease =  NewSalesReport::saleTopAsinIncrease($request['range'], $brand, $request['vendor'], $startDate, $endDate);
        $saleTopAsinShippedCogs =  NewSalesReport::salesTopAsinShippedCOGS($request['range'], $brand, $request['vendor'], $startDate, $endDate);

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
        $shippedCogsAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'shippedCogsSummary', $startDate, $endDate);
        $netReceiptsAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'netReceivedSummary', $startDate, $endDate);
        $poPlanAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'confirmationRateSummary', $startDate, $endDate);
        $yoyAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'yoySummary', $startDate, $endDate);
        $saleTopAsinDecreaseAlerts =  Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'newSaleTopAsinDecrease', $startDate, $endDate);
        $saleTopAsinIncreaseAlerts =  Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'newSaleTopAsinIncrease', $startDate, $endDate);
        $saleTopAsinShippedCogsAlerts = Alerts::getReportedAlerts($request['vendor'], $fk_user_id, 'newSale', $range, 'newSaleTopAsinShippedCogs', $startDate, $endDate);

        return response()->json([
            'shippedCogsGauge' => $shippedCogsGauge,
            'shippedCogsGaugeTrailing' => $shippedCogsGaugeTrailing,
            'netReceiptsGauge' => $netReceiptsGauge,
            'netReceiptsGaugeTrailing' => $netReceiptsGaugeTrailing,
            'poPlanGauge' => $poPlanGauge,
            'poPlanGaugeTrailing' => $poPlanGaugeTrailing,
            'yoyGrowthChart' => $yoyGrowthChart,
            'shippedCogsByGranularityChart' => $shippedCogsByGranularityChart,
            'saleTopAsinDecrease' => $saleTopAsinDecrease,
            'saleTopAsinIncrease' => $saleTopAsinIncrease,
            'saleTopAsinShippedCogs' => $saleTopAsinShippedCogs,
            'shippedCogsAlerts' => $shippedCogsAlerts,
            'shippedCogsGraphAlerts' => $shippedCogsAlerts,
            'netReceiptsAlerts' => $netReceiptsAlerts,
            'poPlanAlerts' => $poPlanAlerts,
            'yoyAlerts' => $yoyAlerts,
            'saleTopAsinDecreaseAlerts' => $saleTopAsinDecreaseAlerts,
            'saleTopAsinIncreaseAlerts' => $saleTopAsinIncreaseAlerts,
            'saleTopAsinShippedCogsAlerts' => $saleTopAsinShippedCogsAlerts,
        ]);
    }

    /**
     * get Subcategory of Vendor.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSubcategory(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $brand = Session()->get('brand_id');

        //call Model Static Function for Calling Store Procedure
        $subcategory =  NewSalesReport::getVendorSubcategory($brand, $request['vendor']);

        return response()->json([
            'subcategory' => $subcategory,
        ]);
    }

    /**
     * get Shipped COGS Rate for Subcategory Value.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSubcategoryShippedCOGS(Request $request)
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

        $subcategory_array =  is_array($request['subcategory']) ? $request['subcategory'] : array();
        $subcategory_mix = implode("|", $subcategory_array); // Use of implode function

        //call Model Static Function for Calling Store Procedure
        $shippedCogsBySubcategoryChart =  NewSalesReport::shippedCogsBySubcategoryChart($request['range'], $brand, $request['vendor'], $startDate, $endDate, $subcategory_mix);

        $date_data = array();
        $date_tooltip = array();
        $subcategory_data = array();
        $data_value = array();

        foreach ($shippedCogsBySubcategoryChart as $chart_data) {
            $data_subcategory_date = $chart_data->sale_date;
            $data_subcategory_date_range = $chart_data->date_range;
            $data_subcategory =  $chart_data->subcategory;

            if (!in_array($data_subcategory_date, $date_data)) {
                $date_data[] = $data_subcategory_date;
            }
            if (!in_array($data_subcategory_date_range, $date_tooltip)) {
                $date_tooltip[] = $data_subcategory_date_range;
            }
            if (!in_array($data_subcategory, $subcategory_data)) {
                $subcategory_data[] = $data_subcategory;
            }
            $data_value[$data_subcategory][$data_subcategory_date] = $chart_data->shipped_cogs;
        }

        $data = array();
        $data['x'] = $date_data;
        foreach ($subcategory_data as $subcat) {
            foreach ($date_data as $date) {
                if (isset($data_value[$subcat][$date])) {
                    $data[$subcat][] = $data_value[$subcat][$date];
                } else {
                    $data[$subcat][] = 0;
                }
            }
        }

        return response()->json([
            'shippedCogsBySubcategoryChart' => $shippedCogsBySubcategoryChart,
            'data' => json_encode($data),
            'date' => $date_data,
            'range' => $date_tooltip,
        ]);
    }

    /**
     * get Net Receipts Rate for Subcategory Value.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSubcategoryNetReceipts(Request $request)
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

        $subcategory_array =  is_array($request['subcategory']) ? $request['subcategory'] : array();
        $subcategory_mix = implode("|", $subcategory_array); // Use of implode function

        //call Model Static Function for Calling Store Procedure
        $netReceiptsBySubcategoryChart =  NewSalesReport::netReceiptsBySubcategoryChart($request['range'], $brand, $request['vendor'], $startDate, $endDate, $subcategory_mix);

        $date_data = array();
        $date_tooltip = array();
        $subcategory_data = array();
        $data_value = array();

        foreach ($netReceiptsBySubcategoryChart as $chart_data) {
            $data_subcategory_date = $chart_data->received_date;
            $data_subcategory_date_range = $chart_data->date_range;
            $data_subcategory =  $chart_data->subcategory;

            if (!in_array($data_subcategory_date, $date_data)) {
                $date_data[] = $data_subcategory_date;
            }
            if (!in_array($data_subcategory_date_range, $date_tooltip)) {
                $date_tooltip[] = $data_subcategory_date_range;
            }
            if (!in_array($data_subcategory, $subcategory_data)) {
                $subcategory_data[] = $data_subcategory;
            }
            $data_value[$data_subcategory][$data_subcategory_date] = $chart_data->net_received;
        }

        $data = array();
        $data['x'] = $date_data;
        foreach ($subcategory_data as $subcat) {
            foreach ($date_data as $date) {
                if (isset($data_value[$subcat][$date])) {
                    $data[$subcat][] = $data_value[$subcat][$date];
                } else {
                    $data[$subcat][] = 0;
                }
            }
        }

        return response()->json([
            'netReceiptsBySubcategoryChart' => $netReceiptsBySubcategoryChart,
            'data' => json_encode($data),
            'date' => $date_data,
            'range' => $date_tooltip,
        ]);
    }

    /**
     * get PO Confirmed Rate for Subcategory Value.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSubcategoryPoConfirmedRate(Request $request)
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
        $poConfirmedRateBySubcategoryChart =  NewSalesReport::poConfirmedRateChart($request['range'], $brand, $request['vendor'], $startDate, $endDate);

        return response()->json([
            'poConfirmedRateBySubcategoryChart' => $poConfirmedRateBySubcategoryChart,
        ]);
    }

    /**
     * get Sales Inventory PO Facts for Subcategory Value.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSIPSubcategoryValue(Request $request)
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

        $subcategory_array =  is_array($request['subcategory']) ? $request['subcategory'] : array();
        $subcategory_mix = implode("|", $subcategory_array); // Use of implode function

        //call Model Static Function for Calling Store Procedure
        //$sipSubcategoryValue =  NewSalesReport::sipSubcategoryValue($request['range'], $brand, $request['vendor'], $startDate, $endDate, $subcategory_mix);

        return response()->json([]);
    }
}
