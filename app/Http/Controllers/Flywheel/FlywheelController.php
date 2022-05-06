<?php

namespace App\Http\Controllers\Flywheel;

use App\Http\Controllers\Controller;
use App\Model\DimVendor;
use App\Model\Flywheel\Flywheel;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FlywheelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        setMemoryLimitAndExeTime();
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
            return view('Flywheel.visual')
                ->with('goldVendors', $GoldVendors)
                ->with('platinumVendors', $PlatinumVendors)
                ->with('silverVendors', $SilverVendors);
        } else {
            return view('Flywheel.visual')
                ->with('goldVendors', array())
                ->with('platinumVendors', array())
                ->with('silverVendors', array());
        }
    }

    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getOrderedRevenueSpAdSalesData(Request $request)
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
        $date_range_flywheel = $request['range'];
        if (!checkDateBussinessRange($date_range_flywheel, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $orderedRevenueSpAdSales = Flywheel::orderedRevenueSpAdSales($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'orderedRevenueSpAdSales' => $orderedRevenueSpAdSales,
        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getConversionsAspData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $conversionsAsptotal =  Flywheel::conversionsAspTotal($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'conversionsAsptotal' => $conversionsAsptotal,
        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSpImpressionsGlanceViewData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $glanceViewSpImpression =  Flywheel::glanceViewSpImpressionTotal($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'glanceViewSpImpression' => $glanceViewSpImpression,

        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getInventoryOrderedUnitData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $inventoryOrderedUnitTotal =  Flywheel::inventoryOrderedUnitTotal($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'inventoryOrderedUnitTotal' => $inventoryOrderedUnitTotal,
        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSpendByAdTypeData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $spendAdType = Flywheel::spendAdType($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'campaignSpendByType' => $spendAdType,
        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSalesByAdTypeData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure

        $salesAdType = Flywheel::salesAdType($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'totalAdSalesByType' => $salesAdType,
        ]);
    }
    /**
     * Get Flywheel detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getCategoryDetailData(Request $request)
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

        if (isset($request['product_info'])) {
            if ($request['product_info'] == null) {
                $product = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['product_info']); $count++) {
                    $product_str[$count] = str_replace("'", '', str_replace(',', '', $request['product_info'][$count]));
                }
                $product_array =  is_array($product_str) ? $product_str : array();
                $product = implode(",", $product_array); // Use of implode function
            }
        } else {
            $product = "NULL";
        }
        if (isset($request['category_info'])) {
            if ($request['category_info'] == null) {
                $category = "NULL";
            } else {
                for ($count = 0; $count < sizeof($request['category_info']); $count++) {
                    $category_str[$count] = str_replace("'", '', str_replace(',', '', $request['category_info'][$count]));
                }
                $category_array =  is_array($category_str) ? $category_str : array();
                $category = implode(",", $category_array); // Use of implode function
            }
        } else {
            $category = "NULL";
        }
        if (isset($request['asin_info'])) {
            $asin_array =  is_array($request['asin_info']) ? $request['asin_info'] : array();
            $asin = implode(",", $asin_array); // Use of implode function
        } else {
            $asin = "NULL";
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $categoryDetailDataTable =  Flywheel::categoryDetailDataTable($request['range'], $vendor_mix, $startDate, $endDate, $product, $category, $asin);

        return response()->json([
            'categoryDetailDataTable' => $categoryDetailDataTable,
        ]);
    }
    public function getdataforselect2(Request $request)
    {
        if (isset($request['vendor'])) {
            $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
            $vendor_mix = implode(",", $vendor_array); // Use of implode function
        } else {
            $vendor_mix = "NULL";
        }
        $dateRange = explode(" - ", $request['range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        //  print_r($vendor_mix);
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts =  Flywheel::getproduct($vendor_mix, $startDate, $endDate, $term);
            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                //  "results" => $posts,
                "pagination" => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }
    public function getdataforAsinselect2(Request $request)
    {
        if (isset($request['vendor'])) {
            $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
            $vendor_mix = implode(",", $vendor_array); // Use of implode function
        } else {
            $vendor_mix = "NULL";
        }
        $dateRange = explode(" - ", $request['range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts =  Flywheel::getAsins($vendor_mix, $startDate, $endDate, $term);
            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }
    public function getdataforCategoryelect2(Request $request)
    {
        if (isset($request['vendor'])) {
            $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
            $vendor_mix = implode(",", $vendor_array); // Use of implode function
        } else {
            $vendor_mix = "NULL";
        }
        $dateRange = explode(" - ", $request['range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts =  Flywheel::getCategory($vendor_mix, $startDate, $endDate, $term);
            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }
}
