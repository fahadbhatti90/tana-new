<?php

namespace App\Http\Controllers\ExecutiveDashboard;

use App\Http\Controllers\Controller;
use App\Model\DimVendor;
use App\Model\edVendor;
use App\Model\ExecutiveDashboard\POPlan;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfirmPOExtended extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userPermission:1,4')->only(['index', 'getEDConfirmPOReport', 'getEDConfirmPOVendorReport', 'setTopEDPOVendor', 'getPOPlan']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('executiveDashboard/ed3_visual');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDConfirmPOReport(Request $request)
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

        if ($request['reported_type'] == 2) {
            if (!checkDateRange(3, $startDate, $endDate)) {
                return response()->json(['error' => 'Your selected date is not valid']);
            }
        } else {
            if (!checkDateRange(5, $startDate, $endDate)) {
                return response()->json(['error' => 'Your selected date is not valid']);
            }
        }
        $user_id = auth()->user()->user_id;
        $role_id = auth()->user()->roles()->get()->first()->role_id;
        $brand_id = Session()->get('brand_id');

        //call Model Static Function for Calling Store Procedure
        $weekly_po_report = \App\Model\ExecutiveDashboard\ConfirmPOExtended::weeklyPOReport($request['type'], $brand_id);
        if ($request['reported_type'] == 2) {
            $po_report_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPOExtended::MonthlyConfirmedPOReport($request['type'], $brand_id, $startDate);
            $po_report_all_vendor_aggregated_values = \App\Model\ExecutiveDashboard\ConfirmPOExtended::AggregatedMonthlyConfirmedPOReport($request['type'], $brand_id, $startDate);
            $po_confirmed_rate_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPOExtended::tanaAllVendorsPOConfirmRateMonthly($brand_id, $startDate);
            if (isset($po_report_all_vendor_aggregated_values[0]->current_accepted_dollar) == true) {
                if (strpos($po_report_all_vendor_aggregated_values[0]->current_accepted_dollar, "-") !== false) {
                    $po_report_all_vendor_aggregated_values = [];
                }
            }
            if (isset($po_report_all_vendor_aggregated_values[0]->current_accepted_units) == true) {
                if (strpos($po_report_all_vendor_aggregated_values[0]->current_accepted_units, "-") !== false) {
                    $po_report_all_vendor_aggregated_values = [];
                }
            }
        } else {
            $po_report_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPOExtended::weeklyConfirmedPOReport($request['type'], $brand_id, $startDate);
            $po_report_all_vendor_aggregated_values = \App\Model\ExecutiveDashboard\ConfirmPOExtended::AggregatedWeeklyConfirmedPOReport($request['type'], $brand_id, $startDate);
            $po_confirmed_rate_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPOExtended::tanaAllVendorsPOConfirmRateWeekly($brand_id, $startDate);

            if (isset($po_report_all_vendor_aggregated_values[0]->current_accepted_dollar) == true) {
                if (strpos($po_report_all_vendor_aggregated_values[0]->current_accepted_dollar, "-") !== false) {
                    $po_report_all_vendor_aggregated_values = [];
                }
            }
            if (isset($po_report_all_vendor_aggregated_values[0]->current_accepted_units) == true) {
                if (strpos($po_report_all_vendor_aggregated_values[0]->current_accepted_units, "-") !== false) {
                    $po_report_all_vendor_aggregated_values = [];
                }
            }
        }
        return response()->json([
            'po_report' => $weekly_po_report,
            'po_report_all_vendor' => $po_report_all_vendor,
            'po_confirmed_rate_all_vendor' => $po_confirmed_rate_all_vendor,
            'po_report_all_vendor_aggregated_values' => $po_report_all_vendor_aggregated_values,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDConfirmPOVendorReport(Request $request)
    {

        $rules = array(
            'date_range' => ['required'],
            'vendor_id' => ['required'],
            'reported_type' => ['required'],
            'report_type' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange = explode(" - ", $request['date_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if ($request['reported_type'] == 2) {
            if (!checkDateRange(3, $startDate, $endDate)) {
                return response()->json(['error' => 'Your selected date is not valid']);
            }
        } else {
            if (!checkDateRange(5, $startDate, $endDate)) {
                return response()->json(['error' => 'Your selected date is not valid']);
            }
        }

        $vendor = auth()->user()->getUserEdVendor()->get()->first();
        if ($request['reported_type'] == 2) {
            //call Model Static Function for Calling Store Procedure
            $vendor_confirmation_rate = \App\Model\ExecutiveDashboard\ConfirmPOExtended::POConfirmRateByVendorMonthly($request['vendor_id'], $startDate, $request['report_type']);
        } else {
            //call Model Static Function for Calling Store Procedure
            $vendor_confirmation_rate = \App\Model\ExecutiveDashboard\ConfirmPOExtended::POConfirmRateByVendor($request['vendor_id'], $startDate, $request['report_type']);
        }
        //dd($vendor_confirmation_rate);
        return response()->json([
            'vendor_confirmation_rate' => $vendor_confirmation_rate,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function setTopEDPOVendor(Request $request)
    {
        $form_data = array(
            'fk_vendor1_id_confirm_po' => isset($request['vendor'][0]) ? $request['vendor'][0] : '0',
            'fk_vendor2_id_confirm_po' => isset($request['vendor'][1]) ? $request['vendor'][1] : '0',
            'fk_vendor3_id_confirm_po' => isset($request['vendor'][2]) ? $request['vendor'][2] : '0',
        );

        edVendor::where("fk_user_id", auth()->user()->user_id)->update($form_data);
        return response()->json(['success' => 'Executive dashboard vendor is changed']);
    }

    /**
     * Set PO Plan.
     * @param Request $request
     * @return JsonResponse
     */
    public function getPOPlan(Request $request)
    {
        $po_value = POPlan::where('name', 'po_value')->first();
        $po_unit = POPlan::where('name', 'po_unit')->first();
        if (!isset($po_value['value'])) {
            $po_value['value'] = 0;
        }
        if (!isset($po_unit['value'])) {
            $po_unit['value'] = 0;
        }
        return response()->json([
            'po_value' => $po_value['value'],
            'po_unit' => $po_unit['value']
        ]);
    }

    /**
     * Set PO Plan.
     * @param Request $request
     * @return JsonResponse
     */
    public function setPOPlan(Request $request)
    {
        $rules = array(
            'po_value' => ['required', 'int'],
            'po_unit' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        POPlan::where('name', 'po_value')->delete();
        POPlan::create([
            'name' => 'po_value',
            'value' => $request['po_value']
        ]);

        POPlan::where('name', 'po_unit')->delete();
        POPlan::create([
            'name' => 'po_unit',
            'value' => $request['po_unit']
        ]);
        return response()->json(['success' => 'PO plan is changed']);
    }
}
