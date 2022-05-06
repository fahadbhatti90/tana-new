<?php

namespace App\Http\Controllers;

use App\Model\Vendors;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use App\Exceptions\Handler;
use App\Model\DailySale;

class DailySalesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor_list = DB::table('mgmt_vendor')
            ->where('marketplace', '!=', '3P')
            ->where('is_active', '=', 1)->get();
        return view('sales.index')->with('vendor_list', $vendor_list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        dd('daily salees');
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
            ['vendor' => 'required', 'daily_sales' => 'required'],
            [
                'daily_sales.required' => 'Sales daily file is required',
                'vendor.required' => 'Vendor field is required'
            ]
        );
        $responseData = array();
        $errorMessage = array();
        $successMessage = array();
        $result = array();
        $error = array();
        if ($validator->passes()) {
            // get Extension
            if ($request->hasFile('daily_sales')) {
                $fileArray = $request->file('daily_sales');
                $arrayLen = count($fileArray);
                $z = 0;
                $count1 = 0;
                //  if ($arrayLen <= 62) {
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
                    $fileExtension = ($request->hasFile('daily_sales') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            list($start, $salesData) = getDataFromExcelFile($file, 'dailysales');
                            // check if sales Data not empty
                            if (!empty($salesData)) {
                                if (isset($salesData[0]['asin']) && isset($salesData[0]['product_title']) && isset($salesData[0]['shipped_cogs']) && isset($salesData[0]['shipped_units']) && isset($salesData[0]['customer_returns']) && isset($salesData[0]['free_replacements'])) {
                                    $storeDailySalesData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $report_date = $start['startdate'];
                                    foreach ($salesData as $data) {
                                        $dbData = $this->dailySalesData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['sale_date'] = $report_date;
                                        $dbData['captured_at'] = date('Y-m-d h:i:s');
                                        array_push($storeDailySalesData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeDailySalesData)) {
                                        // subArraysToString($storeDailySalesData);
                                        foreach (array_chunk($storeDailySalesData, 1000) as $t) {
                                            $sales_data = DailySale::Insertion($t);
                                        }
                                    }
                                    unset($salesData);
                                    unset($storeDailySalesData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded Report!', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Sales Detail Data file is required');
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
                    $errorMessage = array('Sales Detail Data file field is required');
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

    /**
     *  This function is used to gather Daily Sales Data
     * @param $data
     * @return array
     */
    private function dailySalesData($data)
    {
        $dbData = array();
        $dbData['asin'] = (isset($data['asin']) && !empty($data['asin']) ? $data['asin'] : 'NA');
        $dbData['product_title'] = (isset($data['product_title']) && !empty($data['product_title']) ? $data['product_title'] : 'NA');
        $dbData['category'] = (isset($data['category']) && !empty($data['category']) ? $data['category'] : 'NA');
        $dbData['subcategory'] = (isset($data['subcategory']) && !empty($data['subcategory']) ? $data['subcategory'] : 'NA');
        $dbData['shipped_cogs'] = (isset($data['shipped_cogs']) && !empty($data['shipped_cogs']) && strpos($data['shipped_cogs'], '—') === FALSE ? RemoveComma(RemoveDollarSign($data['shipped_cogs'])) : 0);
        $dbData['shipped_cogs_percentage_of_total'] = (isset($data['shipped_cogs_percentage_total']) && !empty($data['shipped_cogs_percentage_total']) ?  RemoveVariations($data['shipped_cogs_percentage_total']) : 0);
        $dbData['shipped_cogs_prior_period'] = (isset($data['shipped_cogs_prior_period']) && !empty($data['shipped_cogs_prior_period']) ? RemoveVariations($data['shipped_cogs_prior_period']) : 0);
        $dbData['shipped_cogs_last_year'] = (isset($data['shipped_cogs_last_year']) && !empty($data['shipped_cogs_last_year'])  ?
            RemoveVariations($data['shipped_cogs_last_year']) : 0);
        $dbData['shipped_units'] = (isset($data['shipped_units']) && !empty($data['shipped_units']) ?  RemoveComma(RemoveDollarSign(RemoveVariations($data['shipped_units']))) : 0);
        $dbData['shipped_units_percentage_of_total'] = (isset($data['shipped_units_percentage_total']) && !empty($data['shipped_units_percentage_total']) ? RemoveVariations($data['shipped_units_percentage_total']) : 0);
        $dbData['shipped_units_prior_period'] = (isset($data['shipped_units_prior_period']) && !empty($data['shipped_units_prior_period']) ? RemoveVariations($data['shipped_units_prior_period']) : 0);
        $dbData['shipped_units_last_year'] = (isset($data['shipped_units_last_year']) && !empty($data['shipped_units_last_year']) ? RemoveVariations($data['shipped_units_last_year']) : 0);
        // $dbData['units_percentage_total'] = (isset($data['units_percentage_total']) && !empty($data['units_percentage_total']) ? PercentageToNull($data['units_percentage_total']) : 0);
        $dbData['customer_returns'] = (isset($data['customer_returns']) && !empty($data['customer_returns']) && strpos($data['customer_returns'], '—') === FALSE ? RemoveDollarSign($data['customer_returns']) : 0);
        $dbData['free_replacements'] = (isset($data['free_replacements']) && !empty($data['free_replacements']) && strpos($data['free_replacements'], '—') === FALSE ? RemoveDollarSign($data['free_replacements']) : 0);
        $dbData['average_sales_price'] = (isset($data['average_sales_price']) && !empty($data['average_sales_price']) && strpos($data['average_sales_price'], '—') === FALSE ? RemoveDollarSign($data['average_sales_price']) : 0);
        $dbData['average_sales_price_prior_period'] = (isset($data['average_sales_price_prior_period']) && !empty($data['average_sales_price_prior_period']) ? PercentageToNull($data['average_sales_price_prior_period']) : 0);
        $dbData['model_no'] = (isset($data['Model / Style Number']) && !empty($data['Model / Style Number']) ? $data['Model / Style Number'] : 0);
        return $dbData;
    }



    function validateExcelFile($file_ext)
    {
        $valid = array(
            'csv', 'xls', 'xlsx' // add your extensions here.
        );
        return in_array($file_ext, $valid) ? true : false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
