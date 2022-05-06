<?php

namespace App\Http\Controllers\BusinessView;

use App\Http\Controllers\Controller;
use App\Model\BusinessView\BusinessView;
use App\Model\DimVendor;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BusinessViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userPermission:1,2')->only(['index', 'getBusinessViewKPI', 'getTotalAdSalesByType', 'getCampaignSpendByType', 'getSearchTermSPData', 'getOrderedSalesSpendData', 'getTopASINData', 'getPortfolioData', 'getLineGrapgByType']);
    } // end function

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
            return view('BusinessView.visual')
                ->with('goldVendors', $GoldVendors)
                ->with('platinumVendors', $PlatinumVendors)
                ->with('silverVendors', $SilverVendors);
        } else {
            return view('BusinessView.visual')
                ->with('goldVendors', array())
                ->with('platinumVendors', array())
                ->with('silverVendors', array());
        } // end else
    } // end function

    /**
     * Get Sales detailed data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getBusinessViewKPI(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if
        if ($request['vendor'] == 0) {
            return response()->json([
                'businessReviewKPI' => [],
            ]);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        //call Model Static Function for Calling Store Procedure
        $marketplace_vendor_1p = "";
        $marketplace_vendor_3p = "";
        foreach ($request['vendor'] as $vendor) {
            $dimVendor = DimVendor::where('rdm_vendor_id', $vendor)->where('is_active', 1)->get()->first();
            $marketplace = $dimVendor->marketplace;
            if ($marketplace == '1P') {
                $marketplace_vendor_1p = "1P";
            }
            if ($marketplace == '3P') {
                $marketplace_vendor_3p = "3P";
            }
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "1P,3P";
        } elseif ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p != "3P") {
            $marketplace = "1P";
        } elseif ($marketplace_vendor_1p != "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "3P";
        }
        //call Model Static Function for Calling Store Procedure
        $businessReviewKPI = BusinessView::businessReviewKPI($marketplace, $request['range'], $vendor_mix, $startDate, $endDate);

        return response()->json([
            'businessReviewKPI' => $businessReviewKPI,
        ]);
    } // end function

    /**
     * Get Total Ad Sales By Type data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getTotalAdSalesByType(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        $brand = Session()->get('brand_id');

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $totalAdSalesByType = BusinessView::totalAdSalesByType($request['range'], $vendor_mix, $startDate, $endDate);
        return response()->json([
            'totalAdSalesByType' => $totalAdSalesByType,
        ]);
    } // end function

    /**
     * Get Campaign Spend By Type data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getCampaignSpendByType(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        $brand = Session()->get('brand_id');

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $campaignSpendByType = BusinessView::campaignSpendByType($request['range'], $vendor_mix, $startDate, $endDate);

        return response()->json([
            'campaignSpendByType' => $campaignSpendByType,
        ]);
    } // end function

    /**
     * Get Search Term SP Data.
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function getSearchTermSPData(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($request->ajax()) {
            //call Model Static Function for Calling Store Procedure
            $data = BusinessView::getSearchTermSpData($request['range'],  $vendor_mix, $startDate, $endDate);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('row', function ($data) {
                    return $data->row;
                })
                ->addColumn('keyword_text', function ($data) {
                    return $data->keyword_text;
                })
                ->addColumn('spend', function ($data) {
                    return $data->spend;
                })
                ->addColumn('ad_sales', function ($data) {
                    return $data->ad_sales;
                })
                ->addColumn('impressions', function ($data) {
                    return $data->impressions;
                })
                ->addColumn('clicks', function ($data) {
                    return $data->clicks;
                })
                ->addColumn('CPC', function ($data) {
                    return $data->CPC;
                })
                ->addColumn('CTR', function ($data) {
                    return $data->CTR;
                })
                ->addColumn('orders', function ($data) {
                    return $data->orders;
                })
                ->addColumn('ROAS', function ($data) {
                    return $data->ROAS;
                })
                ->addColumn('conversion_rate', function ($data) {
                    return $data->conversion_rate;
                })
                ->make(true);
        } // end if
    } // end function

    /**
     * Export Search Term SP Data.
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function exportSearchTermSPData(Request $request)
    {
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        $vendor_array = is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($request->ajax()) {
            //call Model Static Function for Calling Store Procedure
            $data = BusinessView::getSearchTermSpData($request['range'],  $vendor_mix, $startDate, $endDate);
            $data = collect($data)->sortBy('row')->toArray();
            if (is_array($data) && count($data) == 0) {
                return response()->json(['error' => 'There is no data to export'], 404);
            }

            // Data to export and formatting
            $data_array[] = array("RANK", "SEARCH TERM", "SPEND", "AD SALES", "IMPRESSION", "CLICK", "CPC", "CTR", "ORDERS", "ROAS", "CONVERSION RATE");
            foreach ($data as $data_item) {
                $data_array[] = array(
                    'RANK' => $data_item->row,
                    'SEARCH TERM' => $data_item->keyword_text,
                    'SPEND' => floatval(str_replace(",", "", str_replace("$", "", $data_item->spend))),
                    'AD SALES' => floatval(str_replace(",", "", str_replace("$", "", $data_item->ad_sales))),
                    'IMPRESSION' => intval(str_replace(",", "", $data_item->impressions)),
                    'CLICK' => intval(str_replace(",", "", $data_item->clicks)),
                    'CPC' => floatval(str_replace(",", "", str_replace("$", "", $data_item->CPC))),
                    'CTR' => floatval(str_replace("%", "", $data_item->CTR)) / 100,
                    'ORDERS' => intval(str_replace(",", "", $data_item->orders)),
                    'ROAS' => floatval(str_replace(",", "", str_replace("$", "", $data_item->ROAS))),
                    'CONVERSION RATE' => floatval(str_replace("%", "", $data_item->conversion_rate)) / 100,
                );
            }
            $name = "Export_SP_Search_Term_";
            $this->ExportExcel($data_array, $name);
        } // end if
    } // end function
    /**
     * Export Performance Over Time Data.
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function exportPerformanceOverTimeData(Request $request)
    {
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        $marketplace_vendor_1p = "";
        $marketplace_vendor_3p = "";
        foreach ($request['vendor'] as $vendor) {
            $dimVendor = DimVendor::where('rdm_vendor_id', $vendor)->where('is_active', 1)->get()->first();
            $marketplace = $dimVendor->marketplace;
            if ($marketplace == '1P') {
                $marketplace_vendor_1p = "1P";
            }
            if ($marketplace == '3P') {
                $marketplace_vendor_3p = "3P";
            }
        }

        $vendor_array = is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "1P,3P";
        } elseif ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p != "3P") {
            $marketplace = "1P";
        } elseif ($marketplace_vendor_1p != "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "3P";
        }
        if ($request->ajax()) {
            //call Model Static Function for Calling Store Procedure
            $data = BusinessView::getLineGraphByType($marketplace, $request['range'],   $vendor_mix, $startDate, $endDate);
            $data = collect($data)->sortBy('date')->toArray();
            if (is_array($data) && count($data) == 0) {
                return response()->json(['error' => 'There is no data to export'], 404);
            }

            // Data to export and formatting
            $data_array[] = array("DATE", "SPEND", "PRIOR PERIOD SPEND", "AD SALES", "PRIOR PERIOD AD SALES", "ORDERED REVENUE", "GLANCE VIEW", "IMPRESSIONS", "CLICKS", "ROAS", "ORDERS", "CONVERSION RATE");
            foreach ($data as $data_item) {
                $data_array[] = array(
                    'DATE' => $data_item->date,
                    'SPEND' => floatval(str_replace(",", "", str_replace("$", "", $data_item->spend))),
                    'PRIOR PERIOD SPEND' => floatval(str_replace("%", "", $data_item->pre_spend)) / 100,
                    'AD SALES' =>  floatval(str_replace(",", "", str_replace("$", "", $data_item->ad_sales))),
                    'PRIOR PERIOD AD SALES' => floatval(str_replace("%", "", $data_item->pre_ad_sales)) / 100,
                    'ORDERED REVENUE' => floatval(str_replace(",", "", str_replace("$", "", $data_item->order_revenue))),
                    'GLANCE VIEW' => intval(str_replace(",", "", $data_item->glance_views)),
                    'IMPRESSIONS' => intval(str_replace(",", "", $data_item->impressions)),
                    'CLICKS' => intval(str_replace(",", "", $data_item->clicks)),
                    'ROAS' => floatval(str_replace(",", "", str_replace("$", "", $data_item->ROAS))),
                    'ORDERS' => intval(str_replace(",", "", $data_item->orders)),
                    'CONVERSION RATE' => floatval(str_replace("%", "", $data_item->conversion_rate)) / 100,
                );
            }
            $name = "Export_Performance_Over_Time_";
            $this->ExportExcel($data_array, $name);
        } // end if
    } // end function
    /**
     * Export Portfolio Data.
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function exportPortfolioData(Request $request)
    {
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        $vendor_array = is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function

        if ($request->ajax()) {
            //call Model Static Function for Calling Store Procedure
            $data = BusinessView::portfolioKpiData($request['range'], $vendor_mix, $startDate, $endDate);;
            $data = collect($data)->sortBy('portfolios_name')->toArray();
            if (is_array($data) && count($data) == 0) {
                return response()->json(['error' => 'There is no data to export'], 404);
            }

            // Data to export and formatting
            $data_array[] = array("Portfolio", "SPEND", "SPEND (%)", "AD SALES", "AD SALES %", "IMPRESSIONS", "CLICKS", "CPC", "ORDERS",  "ROAS", "CONVERSION RATE");
            foreach ($data as $data_item) {
                $data_array[] = array(
                    'PORTFOLIO' => $data_item->portfolios_name,
                    'SPEND' => floatval(str_replace(",", "", str_replace("$", "", $data_item->cost))),
                    'SPEND (%)' => floatval(str_replace("%", "", $data_item->Percentage_of_spend)) / 100,
                    'AD SALES' =>  floatval(str_replace(",", "", str_replace("$", "", $data_item->campaign_sales))),
                    'AD SALES %' => floatval(str_replace("%", "", $data_item->Percentage_of_sales)) / 100,
                    'IMPRESSIONS' => intval(str_replace(",", "", $data_item->impressions)),
                    'CLICKS' => intval(str_replace(",", "", $data_item->clicks)),
                    'CPC' => floatval(str_replace(",", "", str_replace("$", "", $data_item->CPC))),
                    'ORDERS' => intval(str_replace(",", "", $data_item->orders)),
                    'ROAS' => floatval(str_replace(",", "", str_replace("$", "", $data_item->ROAS))),
                    'CONVERSION RATE' => floatval(str_replace("%", "", $data_item->conversion_rate)) / 100,
                );
            }
            $name = "Export_Portfolio_";
            $this->ExportExcel($data_array, $name);
        } // end if
    } // end function

    /**
     * @param $sp_data
     */
    public function ExportExcel($sp_data, $name)
    {
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);

            //Create Styles Array
            $styleArrayFirstRow = [
                'font' => [
                    'bold' => true,
                ]
            ];

            $total_rows = count($sp_data);

            // Format Columns (https://github.com/PHPOffice/PhpSpreadsheet/blob/master/src/PhpSpreadsheet/Style/NumberFormat.php)
            if ($name == "Export_SP_Search_Term_") {
                $spreadSheet->getActiveSheet()->getStyle('E2:E'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('F2:F'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('I2:I'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');

                $spreadSheet->getActiveSheet()->getStyle('C2:C'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('D2:D'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('G2:G'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('J2:J'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

                $spreadSheet->getActiveSheet()->getStyle('H2:H'.$total_rows)->getNumberFormat()->setFormatCode('0.00%');
                $spreadSheet->getActiveSheet()->getStyle('K2:K'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

                //set first row bold
                $spreadSheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArrayFirstRow);
            }

            if ($name == "Export_Performance_Over_Time_") {
                $spreadSheet->getActiveSheet()->getStyle('G2:G'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('H2:H'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('I2:I'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('K2:K'.$total_rows)->getNumberFormat()->setFormatCode('#,##0');

                $spreadSheet->getActiveSheet()->getStyle('B2:B'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('D2:D'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('F2:F'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('J2:J'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

                $spreadSheet->getActiveSheet()->getStyle('C2:C'.$total_rows)->getNumberFormat()->setFormatCode('0.00%');
                $spreadSheet->getActiveSheet()->getStyle('E2:E'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
                $spreadSheet->getActiveSheet()->getStyle('L2:L'.$total_rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

                //set first row bold
                $spreadSheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArrayFirstRow);
            }

            if ($name == "Export_Portfolio_") {
                $spreadSheet->getActiveSheet()->getStyle('F2:F'.($total_rows+1))->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('G2:G'.($total_rows+1))->getNumberFormat()->setFormatCode('#,##0');
                $spreadSheet->getActiveSheet()->getStyle('I2:I'.($total_rows+1))->getNumberFormat()->setFormatCode('#,##0');

                $spreadSheet->getActiveSheet()->getStyle('B2:B'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('D2:D'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('H2:H'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $spreadSheet->getActiveSheet()->getStyle('J2:J'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

                $spreadSheet->getActiveSheet()->getStyle('C2:C'.($total_rows+1))->getNumberFormat()->setFormatCode('0.00%');
                $spreadSheet->getActiveSheet()->getStyle('E2:E'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
                $spreadSheet->getActiveSheet()->getStyle('K2:K'.($total_rows+1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
                
                // Set Total rows at end
                $spreadSheet->getActiveSheet()->setCellValue("A".($total_rows+1), "Total");
                $spreadSheet->getActiveSheet()->getStyle('A'.($total_rows+1))->applyFromArray($styleArrayFirstRow);
                $spreadSheet->getActiveSheet()->setCellValue("B".($total_rows+1), "=SUM(B2:B".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("C".($total_rows+1), "=SUM(C2:C".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("D".($total_rows+1), "=SUM(D2:D".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("E".($total_rows+1), "=SUM(E2:E".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("F".($total_rows+1), "=SUM(F2:F".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("G".($total_rows+1), "=SUM(G2:G".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("H".($total_rows+1), "=B".($total_rows+1)."/G".($total_rows+1));
                $spreadSheet->getActiveSheet()->setCellValue("I".($total_rows+1), "=SUM(I2:I".$total_rows.")");
                $spreadSheet->getActiveSheet()->setCellValue("J".($total_rows+1), "=D".($total_rows+1)."/B".($total_rows+1));
                $spreadSheet->getActiveSheet()->setCellValue("K".($total_rows+1), "=I".($total_rows+1)."/G".($total_rows+1));

                //set first row bold
                $spreadSheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArrayFirstRow);
            }

            $spreadSheet->getActiveSheet()->fromArray($sp_data, null, 'A1', true);
            $Excel_writer = new Xlsx($spreadSheet);
            $response = new StreamedResponse(
                function () use ($Excel_writer) {
                    $Excel_writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $name . time() . '.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response->send();
        } catch (Exception $e) {
            dd($e);
            return;
        }
    }

    /**
     * Get Ordered Sales Spend Data.
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function getOrderedSalesSpendData(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if
        if ($request['vendor'] == 0) {
            return response()->json([
                'data' => [],
            ]);
        } // end if
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        $marketplace_vendor_1p = "";
        $marketplace_vendor_3p = "";
        foreach ($request['vendor'] as $vendor) {
            $dimVendor = DimVendor::where('rdm_vendor_id', $vendor)->where('is_active', 1)->get()->first();
            $marketplace = $dimVendor->marketplace;
            if ($marketplace == '1P') {
                $marketplace_vendor_1p = "1P";
            }
            if ($marketplace == '3P') {
                $marketplace_vendor_3p = "3P";
            }
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "1P,3P";
        } elseif ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p != "3P") {
            $marketplace = "1P";
        } elseif ($marketplace_vendor_1p != "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "3P";
        }
        if ($request->ajax()) {
            $data =  BusinessView::getLineGraphByType($marketplace, $request['range'],   $vendor_mix, $startDate, $endDate);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($data) {
                    return $data->date;
                })
                ->addColumn('spend', function ($data) {
                    return '$' . number_format($data->spend, 2);
                })
                ->addColumn('pre_spend', function ($data) {
                    return number_format($data->pre_spend, 2) . '%';
                })
                ->addColumn('ad_sales', function ($data) {
                    return '$' . number_format($data->ad_sales, 2);
                })
                ->addColumn('pre_ad_sales', function ($data) {
                    return  number_format($data->pre_ad_sales, 2) . '%';
                })
                ->addColumn('order_revenue', function ($data) {
                    return  isset($data->order_revenue) ? '$' . number_format($data->order_revenue, 2) : "-";
                })
                ->addColumn('glance_views', function ($data) {
                    return number_format($data->glance_views, 0);
                })
                ->addColumn('impressions', function ($data) {
                    return number_format($data->impressions, 0);
                })
                ->addColumn('clicks', function ($data) {
                    return number_format($data->clicks, 0);
                })
                ->addColumn('ROAS', function ($data) {
                    return '$' .  number_format($data->ROAS, 2);
                })
                ->addColumn('orders', function ($data) {
                    return number_format($data->orders, 0);
                })
                ->addColumn('conversion_rate', function ($data) {
                    return number_format($data->conversion_rate, 2) . '%';
                })
                ->make(true);
        } // end if
    } // end function

    /**
     * Get TOP 10 ASINS BY SALES, TOP 5 ASINS INCREASE, and TOP 5 ASINS DECREASE.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getTopASINData(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        $brand = Session()->get('brand_id');
        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $saleTopAsinSales = BusinessView::salesTopAsinSales($request['range'], $vendor_mix, $startDate, $endDate);
        $saleTopAsinDecrease = BusinessView::saleTopAsinDecrease($request['range'], $vendor_mix, $startDate, $endDate);
        $saleTopAsinIncrease = BusinessView::saleTopAsinIncrease($request['range'], $vendor_mix, $startDate, $endDate);

        return response()->json([
            'saleTopAsinSales' => $saleTopAsinSales,
            'saleTopAsinDecrease' => $saleTopAsinDecrease,
            'saleTopAsinIncrease' => $saleTopAsinIncrease,
        ]);
    } // end function
    /**
     * Get Portfolio Kpis Data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getPortfolioData(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        $brand = Session()->get('brand_id');
        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        //call Model Static Function for Calling Store Procedure
        $portfolioKpi = BusinessView::portfolioKpiData($request['range'], $vendor_mix, $startDate, $endDate);
        return response()->json([
            'portfolioKpi' => $portfolioKpi,
        ]);
    } // end function
    /**
     * Get getLineGrapgByType By Type data.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getLineGrapgByType(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if
        if ($request['vendor'] == 0) {
            return response()->json([
                'LineGraphByType' => [],
            ]);
        } // end if
        $marketplace_vendor_1p = "";
        $marketplace_vendor_3p = "";
        foreach ($request['vendor'] as $vendor) {
            $dimVendor = DimVendor::where('rdm_vendor_id', $vendor)->where('is_active', 1)->get()->first();
            $marketplace = $dimVendor->marketplace;
            if ($marketplace == '1P') {
                $marketplace_vendor_1p = "1P";
            }
            if ($marketplace == '3P') {
                $marketplace_vendor_3p = "3P";
            }
        }

        $vendor_array =  is_array($request['vendor']) ? $request['vendor'] : array();
        $vendor_mix = implode(",", $vendor_array); // Use of implode function
        if ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "1P,3P";
        } elseif ($marketplace_vendor_1p == "1P" && $marketplace_vendor_3p != "3P") {
            $marketplace = "1P";
        } elseif ($marketplace_vendor_1p != "1P" && $marketplace_vendor_3p == "3P") {
            $marketplace = "3P";
        }
        //call Model Static Function for Calling Store Procedure
        $LineGraphByType = BusinessView::getLineGraphByType($marketplace, $request['range'],   $vendor_mix, $startDate, $endDate);
        return response()->json([
            'LineGraphByType' => $LineGraphByType,
        ]);
    } // end function
    /**
     * Get date validation.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dateCheck(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'range' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        } // end if

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateBussinessRange($request['range'], $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        } // end if

        return response()->json([
            'success' => 'Your Dates are Valid',
        ]);
    } // end function
} // end class
