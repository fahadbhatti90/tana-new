<?php

namespace App\Http\Controllers\Dropship;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Vendors;
use App\Model\Dropship\Dropship;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Session;
use Helper;
use Carbon\Carbon;

class DropshipController extends Controller
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
        $vendor_list = Vendors::where('is_active', '=', 1)->where('marketplace', '!=', '3P')->orderBy('vendor_name', 'ASC')->get();
        return view('dropship.index')->with('vendor_list', $vendor_list);
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
            ['vendor' => 'required', 'vendor_dropship' => 'required'],
            [
                'vendor_dropship.required' => 'Dropship file is required',
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
            if ($request->hasFile('vendor_dropship')) {
                $fileArray = $request->file('vendor_dropship');
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
                    $fileExtension = ($request->hasFile('vendor_dropship') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            $filePath = uploadCsvFile($file, 'dropship');
                            $dropshipData = readExcelFileSpout($filePath);
                            // check if inventory Data not empty
                            if (!empty($dropshipData)) {
                                if (isset($dropshipData[1]['order_id']) && isset($dropshipData[1]['order_status']) && isset($dropshipData[1]['warehouse_code'])) {
                                    $storeDailydropshipData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $empty = 0;
                                    foreach ($dropshipData as $data) {
                                        if ($empty == 0) {
                                            $empty++;
                                            continue;
                                        }
                                        $dbData = $this->DropshipData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['capture_at'] = date('Y-m-d h:i:s');
                                        array_push($storeDailydropshipData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeDailydropshipData)) {
                                        foreach (array_chunk($storeDailydropshipData, 1000) as $t) {
                                            $result = Dropship::Insertion($t);
                                        }
                                    }
                                    unset($dropshipData);
                                    unset($storeDailySalesData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded report!', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Dropship Data file is required');
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
                    $errorMessage = array('Dropship Data file field is required');
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
    private function DropshipData($data)
    {
        $dbData = array();
        $dbData['order_id'] = (isset($data['order_id']) && !empty($data['order_id']) ? $data['order_id'] : '0');
        $dbData['order_status'] = (isset($data['order_status']) && !empty($data['order_status']) ? $data['order_status'] : 'NA');
        $dbData['warehouse_code'] = (isset($data['warehouse_code']) && !empty($data['warehouse_code']) ? $data['warehouse_code'] : '0');
        $dbData['order_place_date'] = (isset($data['order_place_date']) && !empty($data['order_place_date']) ? Carbon::parse($data['order_place_date'])->format('Y-m-d H:i:s') : '1999-09-09');
        $dbData['required_ship_date'] = (isset($data['required_ship_date']) && !empty($data['required_ship_date']) ? Carbon::parse($data['required_ship_date'])->format('Y-m-d H:i:s') : '1999-09-09');
        $dbData['ship_method'] = (isset($data['ship_method']) && !empty($data['ship_method']) ? $data['ship_method'] : '0');
        $dbData['ship_method_code'] = (isset($data['ship_method_code']) && !empty($data['ship_method_code']) ? $data['ship_method_code'] : '0');
        $dbData['ship_to_name'] = (isset($data['ship_to_name']) && !empty($data['ship_to_name']) ? $data['ship_to_name'] : '0');
        $dbData['ship_to_address_line_1'] = (isset($data['ship_to_address_line_1']) && !empty($data['ship_to_address_line_1']) ? $data['ship_to_address_line_1'] : '0');
        $dbData['ship_to_address_line_2'] = (isset($data['ship_to_address_line_2']) && !empty($data['ship_to_address_line_2']) ? $data['ship_to_address_line_2'] : '0');
        $dbData['ship_to_address_line_3'] = (isset($data['ship_to_address_line_3']) && !empty($data['ship_to_address_line_3']) ? $data['ship_to_address_line_3'] : '0');
        $dbData['ship_to_city'] = (isset($data['ship_to_city']) && !empty($data['ship_to_city']) ? $data['ship_to_city'] : 'NA');
        $dbData['ship_to_state'] = (isset($data['ship_to_state']) && !empty($data['ship_to_state']) ? $data['ship_to_state'] : 'NA');
        $dbData['ship_to_zipcode'] = (isset($data['ship_to_zip_code']) && !empty($data['ship_to_zip_code']) ? $data['ship_to_zip_code'] : '0');
        $dbData['ship_to_country'] = (isset($data['ship_to_country']) && !empty($data['ship_to_country']) ? $data['ship_to_country'] : 'NA');
        $dbData['phone_number'] = (isset($data['phone_number']) && !empty($data['phone_number']) ? $data['phone_number'] : '0');
        $dbData['is_it_gift'] = (isset($data['is_it_gift?']) && !empty($data['is_it_gift?']) ? $data['is_it_gift?'] : 'NA');
        $dbData['item_cost'] = (isset($data['item_cost']) && !empty($data['item_cost']) ? $data['item_cost'] : '0');
        $dbData['sku'] = (isset($data['sku']) && !empty($data['sku']) ? $data['sku'] : 'NA');
        $dbData['asin'] = (isset($data['asin']) && !empty($data['asin']) ? $data['asin'] : 'NA');
        $dbData['item_title'] = (isset($data['item_title']) && !empty($data['item_title']) ? $data['item_title'] : 'NA');
        $dbData['item_quantity'] = (isset($data['item_quantity']) && !empty($data['item_quantity']) ? $data['item_quantity'] : '0');
        $dbData['gift_message'] = (isset($data['gift_message']) && !empty($data['gift_message']) ? $data['gift_message'] : 'NA');
        $dbData['tracking_id'] = (isset($data['tracking_id']) && !empty($data['tracking_id']) ? $data['tracking_id'] : '0');
        $dbData['shipped_date'] = (isset($data['shipped_date']) && !empty($data['shipped_date']) ? Carbon::parse($data['shipped_date'])->format('Y-m-d H:i:s') : '1999-09-09');
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
            $data = Dropship::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('dropship.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        if ($data->Duplicate == 'Yes') {
                            $button .= ' <button type="button"   name="removeDuplication"  id="' . $data->vendor_id . '" title="Remove Duplicate Records" class="removeDuplication btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-x"></i> </button>';
                        }
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('dropship.verify_all');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function moveToCore($id)
    {
        Dropship::moveSelectedDataToCore($id);
        Session::flash('message', 'Dropship data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('dropship/verifyAll');
    }

    public function moveAllToCore()
    {
        Dropship::moveDataToCore();
        Session::flash('message', 'Dropship data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('dropship/verifyAll');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Dropship::deleteAllRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDuplication()
    {
        Dropship::removeDuplicateRecords();
        return response()->json(['success' => 'Duplicate record deleted successfully']);
    }
}
