<?php

namespace App\Http\Controllers\SalesOrderedRevenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Vendors;
use App\Model\SalesOrdered\SalesOrdered;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Session;
use Helper;
use DB;

class SalesOrderedController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,8')->only(['verifyAll', 'verifyByVendor']);
        $this->middleware('permission:2,8')->only(['index', 'store', 'storevendor']);
        $this->middleware('permission:3,8')->only(['moveAllToCore', 'moveToCore']);
        $this->middleware('permission:4,8')->only(['destroyByDate', 'destroy']);
    }
    /**
     * Display uploading module for Sales Ordered Revenue.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor_list = DB::table('mgmt_vendor')
            ->where('marketplace', '!=', '3P')
            ->where('is_active', '=', 1)->get();
        return view('salesOrderedRevenue.index')->with('vendor_list', $vendor_list);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        setMemoryLimitAndExeTime();
        $hiddenFile = $request->input('file_values');
        $hiddenArray = explode(",", $hiddenFile);
        $hiddenLen = count($hiddenArray);
        $validator = Validator::make(
            $request->all(),
            ['vendor' => 'required', 'salesOrderFile' => 'required'],
            [
                'salesOrderFile.required' => 'Sales Ordered Revenue file is required',
                'vendor.required' => 'Vendor field is required'
            ]
        );
        $responseData = array();
        if ($validator->passes()) {
            // get Extension
            if ($request->hasFile('salesOrderFile')) {
                $fileArray = $request->file('salesOrderFile');
                $arrayLen = count($fileArray);
                $z = 0;
                $fkVendorId = $request->input('vendor');
                $isAccountAssociated = Vendors::where('vendor_id', $fkVendorId)
                    ->first();
                foreach ($fileArray as $file) {
                    $flag = false;
                    for ($a = 0; $a < $hiddenLen; $a++) {
                        if ($hiddenArray[$a] == $file->getClientOriginalName()) {
                            $flag = true;
                            break;
                        }
                    }
                    //to remove files that are cross by user
                    if (!$flag) {
                        $z++;
                        continue;
                    }
                    $fileExtension = ($request->hasFile('salesOrderFile') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            list($start, $salesData) = getDataFromExcelFile($file, 'salesOrderedRevenue');
                            // check if sales Data not empty
                            if (!empty($salesData)) {
                                if (isset($salesData[0]['asin']) && isset($salesData[0]['product_title']) && isset($salesData[0]['subcategory'])) {
                                    $storeSalesData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $report_date = $start['startdate'];
                                    foreach ($salesData as $data) {
                                        $dbData = $this->SalesOrderedData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['sale_date'] = $report_date;
                                        array_push($storeSalesData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeSalesData)) {
                                        foreach (array_chunk($storeSalesData, 1000) as $t) {
                                            $sales_data = SalesOrdered::Insertion($t);
                                        }
                                    }
                                    unset($salesData);
                                    unset($storeSalesData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded Report', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Sales Ordered Revenue Data file is required');
                                    $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                                } // End condition of if else
                            } else {
                                $errorMessage = array('Uploaded file is empty kindly upload updated file!');
                                $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                            } // End condition of if else
                        } else {
                            $errorMessage = array('This Account is not associated kindly associate it with any client!');
                            $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                        }
                    } else {
                        $errorMessage = array('File extension should be csv, xls or xlsx');
                        $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                    }
                }
                if ($arrayLen == $z) {
                    $errorMessage = array('Sales Ordered Revenue Data file field is required');
                    $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                }
            } else {
                $errorMessage = array('File does not exist');
                $responseData = array('error' => $errorMessage, 'ajax_status' => false);
            }
        } else {
            $responseData = array('error' => $validator->errors()->all(), 'ajax_status' => false);
        } // End condition of if else of checking validations
        return response()->json($responseData);
    }
    //to validate file
    function validateExcelFile($file_ext)
    {
        $valid = array(
            'csv', 'xls', 'xlsx' // add your extensions here.
        );
        return in_array($file_ext, $valid) ? true : false;
    }
    /**
     *  This function is used to gather Data
     * @param $data
     * @return array
     */
    private function SalesOrderedData($data)
    {
        $dbData = array();
        $dbData['asin'] = (isset($data['asin']) && !empty($data['asin']) ? $data['asin'] : '0');
        $dbData['product_title'] = (isset($data['product_title']) && !empty($data['product_title']) ? $data['product_title'] : '0');
        $dbData['subcategory'] = (isset($data['subcategory']) && !empty($data['subcategory']) ? $data['subcategory'] : '0');
        $dbData['category'] = (isset($data['category']) && !empty($data['category']) ? $data['category'] : '0');
        $dbData['model_number'] = (isset($data['model_number']) && !empty($data['model_number']) ? $data['model_number'] : '0');
        $dbData['ordered_revenue'] = (isset($data['ordered_revenue']) && !empty($data['ordered_revenue']) ? $data['ordered_revenue'] : '0');
        $dbData['ordered_revenue_%_of_total'] = (isset($data['ordered_revenue_%_of_total']) && !empty($data['ordered_revenue_%_of_total']) ? $data['ordered_revenue_%_of_total'] : '0');
        $dbData['ordered_revenue_prior_period'] = (isset($data['ordered_revenue_prior_period']) && !empty($data['ordered_revenue_prior_period']) ? $data['ordered_revenue_prior_period'] : '0');
        $dbData['ordered_revenue_last_year'] = (isset($data['ordered_revenue_last_year']) && !empty($data['ordered_revenue_last_year']) ? $data['ordered_revenue_last_year'] : '0');
        $dbData['ordered_units'] = (isset($data['ordered_units']) && !empty($data['ordered_units']) ? $data['ordered_units'] : '0');
        $dbData['ordered_units_%_of_total'] = (isset($data['ordered_units_%_of_total']) && !empty($data['ordered_units_%_of_total']) ? $data['ordered_units_%_of_total'] : '0');
        $dbData['ordered_units_prior_period'] = (isset($data['ordered_units_prior_period']) && !empty($data['ordered_units_prior_period']) ? $data['ordered_units_prior_period'] : '0');
        $dbData['ordered_units_last_year'] = (isset($data['ordered_units_last_year']) && !empty($data['ordered_units_last_year']) ? $data['ordered_units_last_year'] : '0');
        $dbData['subcategory_sales_rank'] = (isset($data['subcategory_sales_rank']) && !empty($data['subcategory_sales_rank']) ? $data['subcategory_sales_rank'] : '0');
        $dbData['avg_sale_price'] = (isset($data['avg_sale_price']) && !empty($data['avg_sale_price']) ? $data['avg_sale_price'] : '0');
        $dbData['avg_sale_price_prior_period'] = (isset($data['avg_sale_price_prior_period']) && !empty($data['avg_sale_price_prior_period']) ? $data['avg_sale_price_prior_period'] : '0');
        $dbData['glance_views'] = (isset($data['glance_views']) && !empty($data['glance_views']) ? $data['glance_views'] : '0');
        $dbData['glance_views_prior_period'] = (isset($data['glance_views_prior_period']) && !empty($data['glance_views_prior_period']) ? $data['glance_views_prior_period'] : '0');
        $dbData['change_in_GV_last_year'] = (isset($data['change_in_GV_last_year']) && !empty($data['change_in_GV_last_year']) ? $data['change_in_GV_last_year'] : '0');
        $dbData['conversion_rate'] = (isset($data['conversion_rate']) && !empty($data['conversion_rate']) ? $data['conversion_rate'] : '0');
        $dbData['rep_OOS'] = (isset($data['rep_OOS']) && !empty($data['rep_OOS']) ? $data['rep_OOS'] : '0');
        $dbData['rep_OOS_%_of_total'] = (isset($data['rep_OOS_%_of_total']) && !empty($data['rep_OOS_%_of_total']) ? $data['rep_OOS_%_of_total'] : '0');
        $dbData['rep_OOS_prior_period'] = (isset($data['rep_OOS_prior_period']) && !empty($data['rep_OOS_prior_period']) ? $data['rep_OOS_prior_period'] : '0');
        $dbData['LBB_price'] = (isset($data['LBB_price']) && !empty($data['LBB_price']) ? $data['LBB_price'] : '0');
        $dbData['glance_views_prior_period'] = (isset($data['glance_views_prior_period']) && !empty($data['glance_views_prior_period']) ? $data['glance_views_prior_period'] : '0');
        return $dbData;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws Exception
     */
    public function verifyAll(Request $request)
    {
        if ($request->ajax()) {
            $data = SalesOrdered::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('salesOrder.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('salesOrder.verify', $data->vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('salesOrderedRevenue.verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function moveToCore($id)
    {
        SalesOrdered::moveSelectedDataToCore($id);
        Session::flash('message', 'Sales ordered revenue data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('salesOrder/verify_all');
    }

    public function moveAllToCore()
    {
        SalesOrdered::moveDataToCore();
        Session::flash('message', 'Sales ordered revenue data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('salesOrder/verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        SalesOrdered::deleteAllRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroyByDate(Request $request, $id)
    {
        $rules = array(
            'received_date' => ['required', 'date', 'date_format:Y-m-d'],
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        SalesOrdered::deleteSelectedRecord($id, $request['received_date']);
        return response()->json(['success' => 'Record deleted successfully']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function verifyByVendor(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SalesOrdered::fetchDetailData($id);
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if ($data->Duplicate == 'Yes') {
                        $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled" style="display:none;"><i class="feather icon-check-circle"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->SaleDate . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('salesOrderedRevenue.verify')->with('vendor_id', $id);
    }
}
