<?php

namespace App\Http\Controllers\ExecutiveDashboard;

use App\Http\Controllers\Controller;
use App\Model\Alerts\Alerts;
use App\Model\DimVendor;
use App\Model\edVendor;
use App\Model\Vendors;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExecutiveDashboard extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        auth()->user()->getGlobalBrand();
        $data = auth()->user()->getUserSelectedBrandVendor()->where('is_active', 1)->pluck('vendor_id')->all();

        $edVendor = auth()->user()->getUserEdVendor()->get()->first();
        //set ed vendor if not exist
        if (!isset($edVendor->fk_vendor_id_ed)) {
            if (sizeof($data) >= 3) {
                $edVendor = edVendor::create([
                    'fk_user_id' => auth()->user()->user_id,
                    'fk_vendor_id_ed' => $data[0],
                    'fk_vendor1_id_confirm_po' => $data[0],
                    'fk_vendor2_id_confirm_po' => $data[1],
                    'fk_vendor3_id_confirm_po' => $data[2],
                ]);
            } else {
                $edVendor = edVendor::create([
                    'fk_user_id' => auth()->user()->user_id,
                    'fk_vendor_id_ed' => "0",
                    'fk_vendor1_id_confirm_po' => "0",
                    'fk_vendor2_id_confirm_po' => "0",
                    'fk_vendor3_id_confirm_po' => "0",
                ]);
            }
        }
        $data = auth()->user()->getUserSelectedBrandVendor()->where('is_active', 1)->pluck('vendor_id')->all();
        $GoldVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', 'Gold')->get();
        $PlatinumVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', 'Platinum')->get();
        $SilverVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', 'Silver')->get();
        $threeP = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', '(3P)')->get();
        $vendor_info = Vendors::where('vendor_id', $edVendor->fk_vendor_id_ed)->where('is_active', 1)->get()->first();
        $vendor_id = isset($vendor_info) ? $vendor_info->vendor_id : 0;
        $vendor_name = isset($vendor_info) ? $vendor_info->vendor_alias : "-";
        return view('executiveDashboard/ed1_visual')
            ->with('edVendor_id', $vendor_id)
            ->with('edVendor_name', $vendor_name)
            ->with('goldVendors', $GoldVendors)
            ->with('platinumVendors', $PlatinumVendors)
            ->with('threeP', $threeP)
            ->with('silverVendors', $SilverVendors);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDReport(Request $request)
    {
        $rules = array(
            'type' => ['required'],
            'date_range' => ['required'],
            'marketplace_value' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        $orderedProductYtd = [];
        $orderedProductMtd = [];
        $user_id = auth()->user()->user_id;
        $brand_id = Session()->get('brand_id');
        $vendor = auth()->user()->getUserEdVendor()->get()->first();

        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $vendor->fk_vendor_id_ed)->where('is_active', 1)->get()->first();
        $SC_YTD = [];
        $NR_YTD = [];
        $SC_MTD = [];
        $NR_MTD = [];
        $check = [];
        if ($dimVendor->marketplace == '3P') {
            $check = '(3P)';
            $orderedProductYtd = \App\Model\ExecutiveDashboard\ExecutiveDashboard::orderedProductYtd($request['type'], $brand_id, $startDate);
            $orderedProductMtd = \App\Model\ExecutiveDashboard\ExecutiveDashboard::orderedProductMtd($request['type'], $brand_id, $startDate);
        } else {
            //call Model Static Function for Calling Store Procedure
            $SC_YTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsYtd($request['type'], $brand_id, $startDate);
            $NR_YTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedYtd($request['type'], $brand_id, $startDate);
            $SC_MTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsMtd($request['type'], $brand_id, $startDate);
            $NR_MTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedMtd($request['type'], $brand_id, $startDate);
        }

        return response()->json([
            'SC_YTD' => $SC_YTD,
            'NR_YTD' => $NR_YTD,
            'SC_MTD' => $SC_MTD,
            'NR_MTD' => $NR_MTD,
            'orderedProductYtd' => $orderedProductYtd,
            'orderedProductMtd' => $orderedProductMtd,
            'check' => $check,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getVendorDetails(Request $request)
    {
        $rules = array(
            'type' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $vendor = auth()->user()->getUserEdVendor()->get()->first();
        $user_id = auth()->user()->user_id;

        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $vendor->fk_vendor_id_ed)->where('is_active', 1)->get()->first();
        $vendorDetailSC = [];
        $vendorDetailNR = [];
        $vendorDetailROAS = [];
        $vendorDetailSCMTD = [];
        $vendorDetailNRMTD = [];
        $vendorDetailROASMTD = [];
        $saleSummaryAlerts = [];
        $vendorDetailOrderedProductMtd = [];
        $vendorDetailOrderedProductYtd = [];
        $check = [];

        if (isset($dimVendor->rdm_vendor_id)) {
            if ($dimVendor->marketplace != '3P') {
                $check = $dimVendor->marketplace;
                $vendorDetailSC = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailSC($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailNR = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailNR($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailROAS = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailROAS($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailSCMTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailSCMTD($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailNRMTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailNRMTD($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailROASMTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailROASMTD($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $saleSummaryAlerts = Alerts::getReportedAlerts($dimVendor->rdm_vendor_id, $user_id, 'sale', 'monthly', 'summary', $startDate, $endDate);
            } else {
                $check = '(3P)';
                $vendorDetailOrderedProductMtd = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailOrderedProductMtd($request['type'], $dimVendor->rdm_vendor_id, $startDate);
                $vendorDetailOrderedProductYtd = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailOrderedProductYtd($request['type'], $dimVendor->rdm_vendor_id, $startDate);
            }
        }
        return response()->json([
            'vendorDetailSC' => $vendorDetailSC,
            'vendorDetailNR' => $vendorDetailNR,
            'vendorDetailROAS' => $vendorDetailROAS,
            'vendorDetailSCMTD' => $vendorDetailSCMTD,
            'vendorDetailNRMTD' => $vendorDetailNRMTD,
            'vendorDetailROASMTD' => $vendorDetailROASMTD,
            'vendor' => isset($dimVendor->rdm_vendor_id) ? $dimVendor->rdm_vendor_id : 0,
            'vendorAlerts' => $saleSummaryAlerts,
            'vendorDetailOrderedProductMtd' => $vendorDetailOrderedProductMtd,
            'vendorDetailOrderedProductYtd' => $vendorDetailOrderedProductYtd,
            'checkVendorType' => $check,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getShippedCogsTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'type' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        $check = [];
        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $request['vendor'])->where('is_active', 1)->get()->first();
        if ($dimVendor->marketplace === '3P') {
            $check = '3P';
            //call Model Static Function for Calling Store Procedure
            $shippedCogsTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::orderedProductTrailing($request['type'], $request['vendor'], $startDate);
        } else {
            //call Model Static Function for Calling Store Procedure
            $shippedCogsTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsTrailing($request['type'], $request['vendor'], $startDate);
        }
        return response()->json([
            'shippedCogsTrailing' => $shippedCogsTrailing,
            'check' => $check,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getNetReceivedTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'type' => ['required'],
            'date_range' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        //call Model Static Function for Calling Store Procedure
        $netReceivedTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedTrailing($request['type'], $request['vendor'], $startDate);

        return response()->json([
            'netReceivedTrailing' => $netReceivedTrailing,
        ]);
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoasTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'type' => ['required'],
            'date_range' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        //call Model Static Function for Calling Store Procedure
        $roasTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::roasTrailing($request['type'], $request['vendor'], $startDate);

        return response()->json([
            'roasTrailing' => $roasTrailing,
        ]);
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrderedProductTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'type' => ['required'],
            'date_range' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        //call Model Static Function for Calling Store Procedure
        $orderedProductTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::orderedProductTrailing($request['type'], $request['vendor'], $startDate);

        return response()->json([
            'orderedProductTrailing' => $orderedProductTrailing,
        ]);
    }

    /**
     * Set Executive Dashboard Reporting Vendor.
     * @param Request $request
     * @return JsonResponse
     */
    public function setEDVendor(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'fk_vendor_id_ed' => $request['vendor'],
        );
        edVendor::where("fk_user_id", auth()->user()->user_id)->update($form_data);
        $vendor = Vendors::where('vendor_id', $request['vendor'])->where('is_active', 1)->get()->first();
        $vendorName = "-";
        if (isset($vendor->vendor_alias)) {
            $vendorName = $vendor->vendor_alias;
        }
        return response()->json(['success' => 'Executive Dashboard Vendor is changed', 'vendor' => $vendorName]);
    }
    /**
     * get vendor filed values.
     *
     * @return Renderable
     */
    public function getEdVendorForMarketplace(Request $request)
    {
        auth()->user()->getGlobalBrand();
        $data = auth()->user()->getUserSelectedBrandVendor()->where('is_active', 1)->pluck('vendor_id')->all();

        $edVendor = auth()->user()->getUserEdVendor()->get()->first();
        //set ed vendor if not exist
        if (!isset($edVendor->fk_vendor_id_ed)) {
            if (sizeof($data) >= 3) {
                $edVendor = edVendor::create([
                    'fk_user_id' => auth()->user()->user_id,
                    'fk_vendor_id_ed' => $data[0],
                    'fk_vendor1_id_confirm_po' => $data[0],
                    'fk_vendor2_id_confirm_po' => $data[1],
                    'fk_vendor3_id_confirm_po' => $data[2],
                ]);
            } else {
                $edVendor = edVendor::create([
                    'fk_user_id' => auth()->user()->user_id,
                    'fk_vendor_id_ed' => "0",
                    'fk_vendor1_id_confirm_po' => "0",
                    'fk_vendor2_id_confirm_po' => "0",
                    'fk_vendor3_id_confirm_po' => "0",
                ]);
            }
        }

        $AllVendors = [];
        $oneP = [];
        $threeP = [];
        $data = auth()->user()->getUserSelectedBrandVendor()->where('is_active', 1)->pluck('vendor_id')->all();
        if ($request['marketplace_value'] == '0') {
            $AllVendors = DimVendor::whereIN('rdm_vendor_id', $data)->get();
        } elseif ($request['marketplace_value'] == '1') {
            $oneP = DimVendor::whereIN('rdm_vendor_id', $data)->where('marketplace', '1P')->get();
        } elseif ($request['marketplace_value'] == '2') {
            $threeP = DimVendor::whereIN('rdm_vendor_id', $data)->where('marketplace', '3P')->get();
        }
        return response()->json([
            'allVendors' => $AllVendors,
            'oneP' => $oneP,
            'threeP' => $threeP,
        ]);
    }
    /**
     * get Executive dashboard SC/NC table values.
     *
     * @return Renderable
     */
    public function getAllEdTable(Request $request)
    {
        $rules = array(
            'type' => ['required'],
            'date_range' => ['required'],
            'marketplace_value' => ['required'],
            'tooggleTableSc' => ['required'],
            'tooggleTableSc3p' => ['required'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        if ($request['tooggleTableSc'] == '0') {
            $toogle_table = 'MTD';
        } else {
            $toogle_table = 'YTD';
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date
        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        $user_id = auth()->user()->user_id;
        $brand_id = Session()->get('brand_id');
        $vendor = auth()->user()->getUserEdVendor()->get()->first();
        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $vendor->fk_vendor_id_ed)->where('is_active', 1)->get()->first();
        $shippedCogsNCTable = [];
        //call Model Static Function for Calling Store Procedure
        if ($request['marketplace_value'] == 0) {
            if ($dimVendor->marketplace == '3P') {
                $request['marketplace_value'] = 2;
            }
        }
        if ($dimVendor->marketplace == '3P') {
            if ($request['tooggleTableSc3p'] === "false") {
                $toogle_table = 'YTD';
            } else {
                $toogle_table = 'MTD';
            }
        }
        $shippedCogsNCTable =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsNcTable($request['marketplace_value'], $request['type'], $toogle_table, $brand_id, $startDate);
        $shippedCogsNCGrandTotal =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsNCGrandTotal($request['marketplace_value'], $request['type'], $toogle_table, $brand_id, $startDate);
        for ($i = 0; $i < sizeof($shippedCogsNCTable); $i++) {
            $shippedCogsNCTable[$i]->alert = 'no';
            $alert = Alerts::getReportedAlerts($shippedCogsNCTable[$i]->fk_vendor_id, $user_id, 'sale', 'monthly', 'summary', $startDate, $endDate);
            for ($j = 0; $j < sizeof($alert); $j++) {
                switch ($request['type']) {
                    case 0:
                        $shipped_cogs =  (int) (preg_replace('/[\$,]/', '', $shippedCogsNCTable[$i]->shipped_cogs));
                        $reported_value = (int) (preg_replace('/[\$,]/', '', $alert[$j]->reported_value));
                        if ($shipped_cogs == $reported_value && $alert[$j]->reported_attribute == 'shipped_cogs') {
                            $shippedCogsNCTable[$i]->alert = 'yes';
                        }
                        break;
                    case 1:
                        $shipped_units = (int)(preg_replace('/[\$,]/', '', $shippedCogsNCTable[$i]->shipped_units));
                        $reported_value = (int)(preg_replace('/[\$,]/', '', $alert[$j]->reported_value));
                        if ($shipped_units == $reported_value && $alert[$j]->reported_attribute == 'shipped_unit') {
                            $shippedCogsNCTable[$i]->alert = 'yes';
                        }
                        break;
                }
            }
        }
        $check = [];
        if ($dimVendor->marketplace == '3P') {
            $check = "(3P)";
        }
        return response()->json([
            'shippedCogsNcTable' => $shippedCogsNCTable,
            'shippedCogsNCGrandTotal' => $shippedCogsNCGrandTotal,
            'check' => $check,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getScNcTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
            'type' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }
        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if (!checkDateRange(3, $startDate, $endDate)) {
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        $check = [];
        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $request['vendor'])->where('is_active', 1)->get()->first();
        if ($dimVendor->marketplace === '3P') {
            $check = '3P';
            //call Model Static Function for Calling Store Procedure
            $netReceivedTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedTrailing($request['type'], $request['vendor'], $startDate);
            //call Model Static Function for Calling Store Procedure
            $shippedCogsTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::orderedProductTrailing($request['type'], $request['vendor'], $startDate);
        } else {
            //call Model Static Function for Calling Store Procedure
            $netReceivedTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedTrailing($request['type'], $request['vendor'], $startDate);
            //call Model Static Function for Calling Store Procedure
            $shippedCogsTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsTrailing($request['type'], $request['vendor'], $startDate);
        }
        return response()->json([
            'shippedCogsTrailing' => $shippedCogsTrailing,
            'netReceivedTrailing' => $netReceivedTrailing,
            'name' => $dimVendor->vendor_alias,
            'check' => $check,
        ]);
    }
}
