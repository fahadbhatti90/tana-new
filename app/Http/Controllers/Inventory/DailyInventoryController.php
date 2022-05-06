<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Model\Inventory\DailyInventory;
use App\Model\Vendors;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Session;

class DailyInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,8')->only(['verifyAll', 'verifyByVendor']);
        $this->middleware('permission:2,8')->only(['index', 'store', 'storevendor']);
        $this->middleware('permission:3,8')->only(['moveAllToCore', 'moveToCore']);
        $this->middleware('permission:4,8')->only(['destroyByDate', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vendor_list = Vendors::where('is_active', '=', 1)->where('marketplace', '!=', '3P')->orderBy('vendor_name', 'ASC')->get();
        return view('inventory.index')->with('vendor_list', $vendor_list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        setMemoryLimitAndExeTime();
        $hiddenFile = $request->input('file_values');
        $hiddenArray = explode(",", $hiddenFile);
        $hiddenLen = count($hiddenArray);
        $validator = Validator::make(
            $request->all(),
            ['daily_inventory' => 'required'],
            [
                'daily_inventory.required' => 'Inventory daily file is required',
            ]
        );
        $responseData = array();
        $errorMessage = array();
        $successMessage = array();
        $result = array();
        $error = array();
        if ($validator->passes()) {
            // get Extension
            if ($request->hasFile('daily_inventory')) {
                $fileArray = $request->file('daily_inventory');
                $arrayLen = count($fileArray);
                $z = 0;
                $count1 = 0;

                foreach ($fileArray as $file) {
                    $file_name = $file->getClientOriginalName();
                    $flag = false;
                    for ($a = 0; $a < $hiddenLen; $a++) {
                        if ($hiddenArray[$a] == $file_name) {
                            $flag = true;
                            break;
                        }
                    }
                    //to remove files that are cross by user
                    if (!$flag) {
                        $z++;
                        continue;
                    }
                    $fileExtension = ($request->hasFile('daily_inventory') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        $file_info = explode("_", $file_name);
                        $fkVendorId = $file_info[0];
                        $isAccountAssociated = Vendors::where('vendor_id', $fkVendorId)->first();
                        if (!is_null($isAccountAssociated)) {
                            list($start, $inventoryData) = getDataFromExcelFile($file, 'dailyinventory');
                            // check if inventory Data not empty
                            if (!empty($inventoryData)) {
                                if (isset($inventoryData[0]['asin']) && isset($inventoryData[0]['product_title']) && isset($inventoryData[0]['category']) && isset($inventoryData[0]['subcategory'])) {
                                    $storeDailyInventoryData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $report_date = $start['startdate'];
                                    foreach ($inventoryData as $data) {
                                        $dbData = $this->dailyInventoryData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['received_date'] = $report_date;
                                        $dbData['captured_at'] = date('Y-m-d h:i:s');
                                        array_push($storeDailyInventoryData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeDailyInventoryData)) {
                                        foreach (array_chunk($storeDailyInventoryData, 1000) as $t) {
                                            DailyInventory::Insertion($t);
                                        }
                                    }
                                    unset($InventoryData);
                                    unset($storeDailySalesData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded Report!', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Inventory Detail Data file is required');
                                    $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                                } // End condition of if else
                            } else {
                                $errorMessage = array('Uploaded file is empty kindly upload updated file!');
                                $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                            } // End condition of if else
                        } else {
                            $errorMessage = array('Vendor information is required');
                            $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                        }
                    } else {
                        $errorMessage = array('File extension should be csv, xls or xlsx');
                        $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                    }
                }
                if ($arrayLen == $z) {
                    $errorMessage = array('Inventory Detail Data file field is required');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storevendor(Request $request)
    {
        setMemoryLimitAndExeTime();
        $hiddenFile = $request->input('vendor_file_values');
        $hiddenArray = explode(",", $hiddenFile);
        $hiddenLen = count($hiddenArray);
        $validator = Validator::make(
            $request->all(),
            ['vendor' => 'required', 'vendor_daily_inventory' => 'required'],
            [
                'vendor_daily_inventory.required' => 'Inventory daily file is required',
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
            if ($request->hasFile('vendor_daily_inventory')) {
                $fileArray = $request->file('vendor_daily_inventory');
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
                    $fileExtension = ($request->hasFile('vendor_daily_inventory') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            list($start, $inventoryData) = getDataFromExcelFile($file, 'dailyinventory');
                            // check if inventory Data not empty
                            if (!empty($inventoryData)) {
                                if (isset($inventoryData[0]['asin']) && isset($inventoryData[0]['product_title']) && isset($inventoryData[0]['category']) && isset($inventoryData[0]['subcategory'])) {
                                    $storeDailyInventoryData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $report_date = $start['startdate'];
                                    foreach ($inventoryData as $data) {
                                        $dbData = $this->dailyInventoryData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['received_date'] = $report_date;
                                        $dbData['captured_at'] = date('Y-m-d h:i:s');
                                        array_push($storeDailyInventoryData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeDailyInventoryData)) {
                                        foreach (array_chunk($storeDailyInventoryData, 1000) as $t) {
                                            DailyInventory::Insertion($t);
                                        }
                                    }
                                    unset($InventoryData);
                                    unset($storeDailySalesData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded Report!', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Inventory Detail Data file is required');
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
                    $errorMessage = array('Inventory Detail Data file field is required');
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
     *  This function is used to gather Daily Inventory Data
     * @param $data
     * @return array
     */
    private function dailyInventoryData($data)
    {
        $db_data = array();
        $db_data['asin'] = $data['asin'];
        $db_data['product_title'] = isset($data['product_title']) ? $data['product_title'] : '';
        $db_data['category'] = isset($data['category']) ? $data['category'] : '';
        $db_data['subcategory'] = isset($data['subcategory']) ? $data['subcategory'] : '';
        $db_data['model_no'] = isset($data['model_no']) ? $data['model_no'] : '';
        $db_data['net_received'] = $data['net_received'];
        $db_data['net_received_units'] = $data['net_received_units'];
        $db_data['sell_through_rate'] = $data['sellthrough_rate'];
        $db_data['open_purchase_order_quantity'] = $data['open_purchase_order_quantity'];
        $db_data['sellable_on_hand_inventory'] = $data['sellable_on_hand_inventory'];
        $db_data['sellable_on_hand_inventory_trailing_30_day_average'] = isset($data['sellable_on_hand_inventory_trailing_30day_average']) ? $data['sellable_on_hand_inventory_trailing_30day_average'] : (isset($data['sellable_onhand_inventory_trailing_30day_average']) ? $data['sellable_onhand_inventory_trailing_30day_average'] : '');
        $db_data['sellable_on_hand_units'] = $data['sellable_on_hand_units'];
        $db_data['unsellable_on_hand_inventory'] = isset($data['unsellable_on_hand_inventory']) ? $data['unsellable_on_hand_inventory'] : (isset($data['unsellable_onhand_inventory']) ? $data['unsellable_onhand_inventory'] : '');
        $db_data['unsellable_on_hand_inventory_trailing_30_day_average'] = isset($data['unsellable_on_hand_inventory_trailing_30day_average']) ? $data['unsellable_on_hand_inventory_trailing_30day_average'] : (isset($data['unsellable_onhand_inventory_trailing_30day_average']) ? $data['unsellable_onhand_inventory_trailing_30day_average'] : '');
        $db_data['unsellable_on_hand_units'] = isset($data['unsellable_on_hand_units']) ? $data['unsellable_on_hand_units'] : (isset($data['unsellable_onhand_units']) ? $data['unsellable_onhand_units'] : '');
        $db_data['aged_90+_days_sellable_inventory'] = $data['aged_90+_days_sellable_inventory'];
        $db_data['aged_90+_days_sellable_inventory_trailing_30_day_average'] = $data['aged_90+_days_sellable_inventory_trailing_30day_average'];
        $db_data['aged_90+_days_sellable_units'] = $data['aged_90+_days_sellable_units'];
        $db_data['replenishment_category'] = $data['replenishment_category'];
        return $db_data;
    }

    function validateExcelFile($file_ext)
    {
        $valid = array(
            'csv', 'xls', 'xlsx' // add your extensions here.
        );
        return in_array($file_ext, $valid) ? true : false;
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
            $data = DailyInventory::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('inventory.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('inventory.verify', $data->vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('inventory.verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function moveToCore($id)
    {
        DailyInventory::moveSelectedDataToCore($id);
        Session::flash('message', 'Inventory data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('inventory/verify_all');
    }

    public function moveAllToCore()
    {
        DailyInventory::moveDataToCore();
        Session::flash('message', 'Inventory data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('inventory/verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DailyInventory::deleteAllRecord($id);
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

        DailyInventory::deleteSelectedRecord($id, $request['received_date']);
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
            $data = DailyInventory::fetchDetailData($id);
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->Date . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('inventory.verify')->with('vendor_id', $id);
    }
}
