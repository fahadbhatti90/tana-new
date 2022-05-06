<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\AmsProductAdsLoad;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AmsProductAdsLoadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyProductAds', 'loadWeeklyProductAds', 'loadMonthlyProductAds']);
    } // end function

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ams.amsVerify.productAdsLoad');
    } // end function

    /**
     * Load Daily ProductAds Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyProductAds(Request $request)
    {
        $rules = array(
            'load_daily_product_ads_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        }

        $dateRange = explode(" - ", $request['load_daily_product_ads_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyProductAdsResponse = AmsProductAdsLoad::loadDailyProductAds($startDate, $endDate);

        return response()->json([
            'success' => 'ProductAds daily records are successfully loaded',
            'response' => $dailyProductAdsResponse
        ]);
    }

    /**
     * Load Weekly ProductAds Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadWeeklyProductAds(Request $request)
    {
        $rules = array(
            'load_weekly_product_ads_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_weekly_product_ads_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(2, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid week range']);
        }
        //call Model Static Function for Calling Store Procedure
        $dailyProductAdsResponse = AmsProductAdsLoad::loadWeeklyProductAds($startDate, $endDate);

        return response()->json([
            'success' => 'ProductAds weekly records are successfully loaded',
            'response' => $dailyProductAdsResponse
        ]);
    }

    /**
     * Load Monthly ProductAds Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMonthlyProductAds(Request $request)
    {
        $rules = array(
            'load_monthly_product_ads_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        $dateRange = explode(" - ", $request['load_monthly_product_ads_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($error->fails() or !checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Please select valid month range']);
        }

        //call Model Static Function for Calling Store Procedure
        $dailyProductAdsResponse = AmsProductAdsLoad::loadMonthlyProductAds($startDate, $endDate);

        return response()->json([
            'success' => 'ProductAds monthly records are successfully loaded',
            'response' => $dailyProductAdsResponse
        ]);
    }

    //Detail pages for Search Term sd
    public function dashboard(Request $request)
    {
        if ($request->ajax()) {

            $data = AmsProductAdsLoad::getDashboardRecord();
            return DataTables::of($data)
                ->make(true);
        }
        return view('ams.amsVerify.productAdsLoad');
    }
}
