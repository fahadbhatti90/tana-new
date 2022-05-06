<?php

namespace App\Http\Controllers\SellerCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Vendors;
use App\Model\SellerCentral\SellerCentral;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Session;
use Helper;
use Carbon\Carbon;

class SellerCenterController extends Controller
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
     * Display uploading module for Dropship.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor_list = Vendors::where('is_active', '=', 1)->where('marketplace', '3P')->orderBy('vendor_name', 'ASC')->get();
        return view('sellerCenter.index')->with('vendor_list', $vendor_list);
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
            ['vendor' => 'required', 'sellerCentralFile' => 'required'],
            [
                'sellerCentralFile.required' => 'Seller Central file is required',
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
            if ($request->hasFile('sellerCentralFile')) {
                $fileArray = $request->file('sellerCentralFile');
                $arrayLen = count($fileArray);
                $z = 0;
                $count1 = 0;
                $fkVendorId = $request->input('vendor');
                $isAccountAssociated = Vendors::where('vendor_id', $fkVendorId)->first();
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
                    $fileExtension = ($request->hasFile('sellerCentralFile') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            $filePath = uploadCsvFile($file, 'sellerCentral');
                            $sellerData = readExcelFileSpout($filePath);
                            // check if inventory Data not empty
                            if (!empty($sellerData)) {
                                if (isset($sellerData[1]['date']) && isset($sellerData[1]['ordered_product_sales']) && isset($sellerData[1]['units_ordered'])) {
                                    $storeSellerCenterData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $empty = 0;
                                    foreach ($sellerData as $data) {
                                        if ($empty == 0) {
                                            $empty++;
                                            continue;
                                        }
                                        $dbData = $this->SellerCentralData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['capture_at'] = date('Y-m-d h:i:s');
                                        array_push($storeSellerCenterData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeSellerCenterData)) {
                                        foreach (array_chunk($storeSellerCenterData, 1000) as $t) {
                                            $result = SellerCentral::Insertion($t);
                                        }
                                    }
                                    unset($sellerData);
                                    unset($storeSellerCenterData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded report!', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('SC Sale Data file is required');
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
                    $errorMessage = array('SC Sale Data file field is required');
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
    private function SellerCentralData($data)
    {
        $dbData = array();
        $dbData['sale_date'] = (isset($data['date']) && !empty($data['date']) ? Carbon::parse($data['date'])->format('Y-m-d H:i:s') : '1999-09-09');
        $dbData['ordered_product_sales'] = (isset($data['ordered_product_sales']) && !empty($data['ordered_product_sales']) ? $data['ordered_product_sales'] : '0');
        $dbData['units_ordered'] = (isset($data['units_ordered']) && !empty($data['units_ordered']) ? $data['units_ordered'] : '0');
        $dbData['total_ordered_items'] = (isset($data['total_order_items']) && !empty($data['total_order_items']) ? $data['total_order_items'] : '0');
        $dbData['average_sales_per_order_item'] = (isset($data['average_sales_per_order_item']) && !empty($data['average_sales_per_order_item']) ? $data['average_sales_per_order_item'] : '0');
        $dbData['average_units_per_order_item'] = (isset($data['average_units_per_order_item']) && !empty($data['average_units_per_order_item']) ? $data['average_units_per_order_item'] : '0');
        $dbData['average_selling_price'] = (isset($data['average_selling_price']) && !empty($data['average_selling_price']) ? $data['average_selling_price'] : '0');
        $dbData['sessions'] = (isset($data['sessions']) && !empty($data['sessions']) ? $data['sessions'] : '0');
        $dbData['order_item_session_percentage'] = (isset($data['order_item_session_percentage']) && !empty($data['order_item_session_percentage']) ? $data['order_item_session_percentage'] : '0');
        $dbData['average_offer_count'] = (isset($data['average_offer_count']) && !empty($data['average_offer_count']) ? $data['average_offer_count'] : '0');
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
            $data = SellerCentral::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('sellerCenter.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('sellerCenter.verify', $data->vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('sellerCenter.verify_all');
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
            $data = SellerCentral::fetchDetailData($id);
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->sale_date . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('sellerCenter.verify')->with('vendor_id', $id);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function moveToCore($id)
    {
        SellerCentral::moveSelectedDataToCore($id);
        Session::flash('message', 'SC Sales data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('sellerCenter/verifyAll');
    }

    public function moveAllToCore()
    {
        SellerCentral::moveDataToCore();
        Session::flash('message', 'SC Sales data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('sellerCenter/verifyAll');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        SellerCentral::deleteAllRecord($id);
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

        SellerCentral::deleteSelectedRecord($id, $request['received_date']);
        return response()->json(['success' => 'Record deleted successfully']);
    }
}
