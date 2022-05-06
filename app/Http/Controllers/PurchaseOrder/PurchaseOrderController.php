<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Model\VerifySales;
use App\Model\Vendors;
use App\Model\purchaseOrder\PurchaseOrder;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Helper;

class PurchaseOrderController extends Controller
{
    /**
     * LoadSalesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,8')->only(['index', 'AssociatedVendors']);
        $this->middleware('permission:3,8')->only(['moveToCore', 'saleToCore']);
        $this->middleware('permission:4,8')->only(['destroyVendor', 'destroy']);
        // $this->middleware('permission:3,8')->only(['index', 'loadDailySales', 'loadWeeklySales', 'loadMonthlySales']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $vendors_list = PurchaseOrder::fetchVendors();
        return view('purchaseOrder.index', array('vendors_list' => $vendors_list));
    }

    public function purchaseOrderStoreRecords(Request $request)
    {
        // Set Memory Limit And Execution Time
        ini_set('max_execution_time', 0); //set max execution time
        ini_set("memory_limit", "-1"); // set memory limit
        $responseErrorArray = array();
        $responseSuccessArray = array();
        $errorMessage = array();
        $successMessage = array();
        $responseData = array();

        // to validate by name
        if ($request->hasFile('open_agg_file')) {
            if ($request->file('open_agg_file')->getClientOriginalName() != 'ConfirmedPurchaseOrders.xlsx') {
                $responseErrorArray = array('error' => array('Open agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }
        if ($request->hasFile('open_nonagg_file')) {
            if ($request->file('open_nonagg_file')->getClientOriginalName() != 'ConfirmedPurchaseOrderItems.xlsx') {
                $responseErrorArray = array('error' => array('Open non agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }

        if ($request->hasFile('close_agg_file')) {
            if ($request->file('close_agg_file')->getClientOriginalName() != 'OrderHistory.xlsx') {
                $responseErrorArray = array('error' => array('Close agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }
        if ($request->hasFile('close_nonagg_file')) {
            if ($request->file('close_nonagg_file')->getClientOriginalName() != 'PurchaseOrderItems.xlsx') {
                $responseErrorArray = array('error' => array('Close non agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }

        // check open files
        if ($request->hasFile('open_agg_file')) {
            if (!$request->hasFile('open_nonagg_file')) {
                $responseErrorArray = array('error' => array('Open non agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        } else if ($request->hasFile('open_nonagg_file')) {
            if (!$request->hasFile('open_agg_file')) {
                $responseErrorArray = array('error' => array('Open agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }
        // check close files
        if ($request->hasFile('close_agg_file')) {
            if (!$request->hasFile('close_nonagg_file')) {
                $responseErrorArray = array('error' => array('Close non agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        } else if ($request->hasFile('close_nonagg_file')) {
            if (!$request->hasFile('close_agg_file')) {
                $responseErrorArray = array('error' => array('Close agg file required'), 'ajax_status' => false);
                return response()->json($responseErrorArray);
            }
        }
        if (!$request->hasFile('close_agg_file') && !$request->hasFile('close_nonagg_file') && !$request->hasFile('open_agg_file') && !$request->hasFile('open_nonagg_file')) {
            $responseErrorArray = array('error' => array('Kindly upload the required files'), 'ajax_status' => false);
            return response()->json($responseErrorArray);
        }

        // If validation Passes e.g no errors
        // get Extension of upload files
        $fileExtensionOpenAgg = ($request->hasFile('open_agg_file') ? $request->file('open_agg_file')->getClientOriginalExtension() : '');
        $fileExtensionOpenNonAgg = ($request->hasFile('open_nonagg_file') ? $request->file('open_nonagg_file')->getClientOriginalExtension() : '');
        $fileExtensionCloseAgg = ($request->hasFile('close_agg_file') ? $request->file('close_agg_file')->getClientOriginalExtension() : '');
        $fileExtensionNonCloseAgg = ($request->hasFile('close_nonagg_file') ? $request->file('close_nonagg_file')->getClientOriginalExtension() : '');
        $storeDataArray = [];
        $closeStoreDataArray = [];
        $storeAsinsScProduct = [];
        //$asinsToStore = [];
        $fkVendorId = $request->input('vendor');
        $date = date('01-m-Y');
        $isAccountAssociated = PurchaseOrder::checkVendors($fkVendorId);
        // $isAccountAssociated = Vendors::where('vendor_id', $fkVendorId)
        //     ->first();
        if (!is_null($isAccountAssociated)) {

            /*****************************************
                Open AGG and NON-AGG FILE DATA     
             *****************************************/
            // Check file validation Open Agg and Open Non Agg
            if ($this->validateExcelFile($fileExtensionOpenAgg) != false && $this->validateExcelFile($fileExtensionOpenNonAgg) != false) {
                // Agg File Open
                $fileUploadedOpenAggPath = uploadCsvFile($request->file('open_agg_file'), 'purchaseorder');
                // check if file uploaded successfully
                if ($fileUploadedOpenAggPath != FALSE) {
                    // Read open AGG file
                    $getDataFromOpenAggFile = readExcelFileSpout($fileUploadedOpenAggPath);
                    // if not empty open AGG file
                    if (!empty($getDataFromOpenAggFile)) {
                        // validate the columns e.g make sure it is open Agg file
                        if (isset($getDataFromOpenAggFile[1]['poid']) || isset($getDataFromOpenAggFile[1]['po']) && isset($getDataFromOpenAggFile[1]['orderdate']) || isset($getDataFromOpenAggFile[1]['ordered_on'])) {
                            $singleEntry['openAgg'] = array();
                            // making array for data collection
                            $count1 = 0;
                            foreach ($getDataFromOpenAggFile as $index) {
                                if ($count1 == 0) {
                                    $count1++;
                                    continue;
                                }
                                $openAggData['po'] = isset($index['poid']) ? $index['poid'] : $index['po'];
                                if (isset($index['orderdate']) && !empty($index['orderdate'])) {
                                    $openAggData['ordered_on'] = $index['orderdate'];
                                } elseif (isset($index['ordered_on']) && !empty($index['ordered_on'])) {
                                    $openAggData['ordered_on'] = $index['ordered_on'];
                                }
                                $openAggData['status'] = 'Confirmed';
                                array_push($singleEntry['openAgg'], $openAggData);
                            } // End Foreach Loop
                            $storeDataArray = $singleEntry;
                        } else {
                            array_push($errorMessage, 'Uploaded file is not valid kindly upload open agg  file!');
                            $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                        } // End validate file
                    } else {
                        array_push($errorMessage, 'Uploaded file is empty kindly upload open agg updated file!');
                        $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                    }
                } else {
                    array_push($errorMessage, 'File open agg not uploaded successfully!');
                    $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                } // End condition ---> check if file uploaded successfully

                if (!empty($storeDataArray)) {
                    // Open Non Agg File OPEN
                    $fileUploadedOpenNonAggPath = uploadCsvFile($request->file('open_nonagg_file'), 'purchaseorder');
                    // check if file uploaded successfully
                    if ($fileUploadedOpenNonAggPath != FALSE) {
                        // Read open Non AGG file

                        $getDataFromOpenNonAggFile = readExcelFileSpout($fileUploadedOpenNonAggPath);
                        // if not empty open Non AGG file
                        if (!empty($getDataFromOpenNonAggFile)) {
                            // validate the columns e.g make sure it is open non Agg file
                            if (
                                isset($getDataFromOpenNonAggFile[1]['po'])
                                && isset($getDataFromOpenNonAggFile[1]['vendor']) && isset($getDataFromOpenNonAggFile[1]['asin'])
                                && isset($getDataFromOpenNonAggFile[1]['title'])
                                && isset($getDataFromOpenNonAggFile[1]['total_cost'])
                            ) {
                                $singleEntry['openNonAgg'] = array();
                                // making array for data collection
                                $count2 = 0;
                                foreach ($getDataFromOpenNonAggFile as $index) {
                                    if ($count2 == 0) {
                                        $count2++;
                                        continue;
                                    }
                                    $data = $this->NonAggFileData($index);
                                    $data['fk_vendor_id'] = $fkVendorId;
                                    // check if open non agg file column po is equal to open agg po column.
                                    foreach ($storeDataArray['openAgg'] as $openAgg) {
                                        if ($openAgg['po'] == $data['po']) {
                                            $orderOpenAggDate = (isset($openAgg['ordered_on']) && !empty($openAgg['ordered_on']) ? dateConversion($openAgg['ordered_on']) : '1999-09-09');
                                        }
                                    }
                                    $data['ordered_on'] = (isset($orderOpenAggDate) && !empty($orderOpenAggDate)) ? $orderOpenAggDate : '1999-09-09';
                                    $data['status'] = 'Confirmed';
                                    $data['captured_at'] = dateConversion(date('Y-m-d'));

                                    array_push($singleEntry['openNonAgg'], $data);
                                } // End foreach Loop
                                $storeDataArray = $singleEntry;
                            } else {
                                array_push($errorMessage, 'Uploaded file is not valid kindly upload open non agg  file!');
                                $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                            } // End Condition -->  validate the columns
                        } else {
                            array_push($errorMessage, 'Uploaded file is empty kindly upload open non agg  updated file!');
                            $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                        } // End condition -->  if not empty open Non AGG file
                    } else {
                        array_push($errorMessage, 'File open non agg not uploaded successfully!');
                        $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                    } // End condition --> check if file uploaded successfully


                    // check if Array Is not empty then insert data into DB OPEN
                    if (!empty($storeDataArray['openNonAgg'])) {
                        $request->session()->put('fk_vendor_id', $fkVendorId);
                        PurchaseOrder::insertPoData($storeDataArray['openNonAgg']);
                        // ASIN's Insetion
                        // PurchaseOrder::insertAsins($storeAsinsScProduct);
                        array_push($successMessage, 'Open agg and non-agg file uploaded successfully!        ');
                        $responseSuccessArray = array('success' => $successMessage, 'ajax_status' => true);
                        unset($storeDataArray);
                        unset($data);
                        unset($getDataFromOpenNonAggFile);
                    }
                } else {
                    $responseErrorArray = array('error' => array('Open agg file required'), 'ajax_status' => false);
                    return response()->json($responseErrorArray);
                }
            }

            /*****************************************
                Close AGG and NON-AGG FILE DATA     
             *****************************************/

            // Check file validation Close Agg and Close Non Agg
            if ($this->validateExcelFile($fileExtensionCloseAgg) != false && $this->validateExcelFile($fileExtensionNonCloseAgg) != false) {
                // Close Agg File
                $fileUploadedCloseAggPath = uploadCsvFile($request->file('close_agg_file'), 'purchaseorder');
                // check if file uploaded successfully
                if ($fileUploadedCloseAggPath != FALSE) {
                    // Read close Agg File
                    $getDataFromCloseAggFile = readExcelFileSpout($fileUploadedCloseAggPath);
                    // if not empty close AGG file
                    if (!empty($getDataFromCloseAggFile)) {
                        // validate the columns e.g make sure it is close Agg file
                        if (isset($getDataFromCloseAggFile[1]['poid']) || isset($getDataFromCloseAggFile[1]['po']) && isset($getDataFromCloseAggFile[1]['orderdate']) || isset($getDataFromCloseAggFile[1]['ordered_on'])) {
                            $singleEntry['closeAgg'] = array();
                            // making array for data collection
                            $count3 = 0;
                            foreach ($getDataFromCloseAggFile as $index) {
                                if ($count3 == 0) {
                                    $count3++;
                                    continue;
                                }
                                $closeAggData['po'] = isset($index['poid']) ? $index['poid'] : $index['po'];
                                if (isset($index['orderdate']) && !empty($index['orderdate'])) {
                                    $closeAggData['ordered_on'] = $index['orderdate'];
                                } elseif (isset($index['ordered_on']) && !empty($index['ordered_on'])) {
                                    $closeAggData['ordered_on'] = $index['ordered_on'];
                                }
                                $closeAggData['status'] = 'Closed';
                                array_push($singleEntry['closeAgg'], $closeAggData);
                            } // End foreach loop
                            $closeStoreDataArray = $singleEntry;
                        } else {
                            array_push($errorMessage, 'Uploaded file is not valid kindly upload close agg  file!');
                            $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                        } // End validate file
                    } else {
                        array_push($errorMessage, 'Uploaded file is empty kindly upload close agg  updated file!');
                        $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                    } // End condtion -->  if not empty close AGG file
                } else {
                    array_push($errorMessage, 'File close agg not uploaded successfully!');
                    $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                }
                if (!empty($closeStoreDataArray)) {
                    // Non Agg File Close
                    $fileUploadedCloseNonAggPath = uploadCsvFile($request->file('close_nonagg_file'), 'purchaseorder');
                    if ($fileUploadedCloseNonAggPath != FALSE) {
                        // Read close Non AGG file
                        $getDataFromCloseNonAggFile = readExcelFileSpout($fileUploadedCloseNonAggPath);
                        // check if file uploaded successfully
                        if (!empty($getDataFromCloseNonAggFile)) {
                            // validate the columns e.g make sure it is close non Agg file
                            if (
                                isset($getDataFromCloseNonAggFile[1]['po'])
                                && isset($getDataFromCloseNonAggFile[1]['vendor']) && isset($getDataFromCloseNonAggFile[1]['asin'])
                                && isset($getDataFromCloseNonAggFile[1]['title'])
                                &&  isset($getDataFromCloseNonAggFile[1]['total_cost'])
                            ) {
                                $singleEntry['closeNonAgg'] = array();
                                // making array for data collection
                                $count4 = 0;
                                foreach ($getDataFromCloseNonAggFile as $index) {
                                    if ($count4 == 0) {
                                        $count4++;
                                        continue;
                                    }
                                    $data = $this->NonAggFileData($index);
                                    $data['fk_vendor_id'] = $fkVendorId;
                                    foreach ($closeStoreDataArray['closeAgg'] as $closeAgg) {
                                        if ($closeAgg['po'] == $data['po']) {
                                            $orderCloseAggDate = (isset($closeAgg['ordered_on']) && !empty($closeAgg['ordered_on']) ? dateConversion($closeAgg['ordered_on']) : '1999-09-09');
                                        }
                                    }
                                    $data['ordered_on'] = (isset($orderCloseAggDate) && !empty($orderCloseAggDate)) ? $orderCloseAggDate : '1999-09-09';
                                    $data['status'] = 'Closed';
                                    $data['captured_at'] = dateConversion(date('Y-m-d'));
                                    array_push($singleEntry['closeNonAgg'], $data);
                                } // End foreach loop
                                $closeStoreDataArray = $singleEntry;
                            } else {
                                array_push($errorMessage, 'Uploaded file is not valid kindly upload close Non AGG  file!');
                                $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                            } // End Validation --> validate  columns
                        } else {
                            array_push($errorMessage, 'Uploaded file is empty kindly upload close non agg  updated file!');
                            $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                        } // End condtion -->  if not empty close non AGG file

                    } else {
                        array_push($errorMessage, 'File close non agg not upload successfully!');
                        $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
                    }
                } else {
                    $responseErrorArray = array('error' => array('Close agg File required'), 'ajax_status' => false);
                    return response()->json($responseErrorArray);
                }
                // Close Agg Function DATA INSERT
                if (!empty($closeStoreDataArray['closeNonAgg'])) {
                    // Insetion of PO Data
                    PurchaseOrder::insertPoData($closeStoreDataArray['closeNonAgg']);
                    // Insertion Of ASIN's
                    // VCModel::insertAsins($storeAsinsScProduct);
                    array_push($successMessage, 'Close agg and non-agg file uploaded successfully!');
                    $responseSuccessArray = array('success' => $successMessage, 'ajax_status' => true);
                    unset($closeStoreDataArray);
                    unset($data);
                    unset($singleEntry);
                    unset($getDataFromCloseAggFile);
                }
            }
        } else {
            array_push($errorMessage, 'This account is not associated kindly associate it with any brand!');
            $responseErrorArray = array('error' => $errorMessage, 'ajax_status' => false);
        }
        $responseData = $responseErrorArray + $responseSuccessArray;
        return response()->json($responseData);
    }
    private function nonAggFileData($index)
    {
        if (isset($index['poid']) && !empty($index['poid'])) {
            $data['po'] = $index['poid'];
        } elseif (isset($index['po']) && !empty($index['po'])) {
            $data['po'] = $index['po'];
        } else {
            $data['po'] = 'NA';
        }
        $data['vendor'] = (isset($index['vendor']) && !empty($index['vendor']) ? $index['vendor'] : 'NA');
        if (isset($index['ship_to_location']) && !empty($index['ship_to_location'])) {
            $data['ship_to_location'] = $index['ship_to_location'];
        } elseif (isset($index['shiplocation']) && !empty($index['shiplocation'])) {
            $data['ship_to_location'] = $index['shiplocation'];
        } else {
            $data['ship_to_location'] = 'NA';
        }

        if (isset($index['model_number']) && !empty($index['model_number'])) {
            $data['model_number'] = $index['model_number'];
        } elseif (isset($index['modelnumber']) && !empty($index['modelnumber'])) {
            $data['model_number'] = $index['modelnumber'];
        } else {
            $data['model_number'] = 'NA';
        }
        $data['asin'] = (isset($index['asin']) && !empty($index['asin']) ? $index['asin'] : 'NA');
        $data['availability'] = (isset($index['availability']) && !empty($index['availability']) ? $index['availability'] : 'NA');
        $data['product_title'] = (isset($index['title']) && !empty($index['title']) ? $index['title'] : 'NA');
        if (isset($index['window_type']) && !empty($index['window_type'])) {
            $data['window_type'] = $index['window_type'];
        } else {
            $data['window_type'] = 'NA';
        }

        if (isset($index['external_id']) && !empty($index['externalid'])) {
            $data['external_id'] = $index['externalid'];
        } elseif (isset($index['external_id']) && !empty($index['external_id'])) {
            $data['external_id'] = $index['external_id'];
        } else {
            $data['external_id'] = 'NA';
        }

        if (isset($index['window_start']) && !empty($index['window_start'])) {
            $data['window_start'] = dateConversion($index['window_start']);
        } else {
            $data['window_start'] = 'NA';
        }
        // $data['status'] = (isset($index['status']) && !empty($index['status']) ? $index['status'] : 'NA');
        if (isset($index['window_end']) && !empty($index['window_end'])) {
            $data['window_end'] = dateConversion($index['window_end']);
        } else {
            $data['window_end'] = 'NA';
        }
        if (isset($index['backordered']) && !empty($index['backorder'])) {
            $data['backordered'] = $index['backorder'];
        } elseif (isset($index['backordered']) && !empty($index['backordered'])) {
            $data['backordered'] = $index['backordered'];
        } else {
            $data['backordered'] = 'NA';
        }
        if (isset($index['expected_date']) && !empty($index['expected_date'])) {
            $data['expected_date'] = dateConversion($index['expected_date']);
        } else {
            $data['expected_date'] = '1999-09-09';
        }
        if (isset($index['quantity_requested']) && !empty($index['quantity_requested'])) {
            $data['quantity_requested'] = $index['quantity_requested'];
        } else {
            $data['quantity_requested'] = 0;
        }
        if (isset($index['accepted_quantity']) && !empty($index['accepted_quantity'])) {
            $data['accepted_quantity'] = $index['accepted_quantity'];
        } else {
            $data['accepted_quantity'] = 0;
        }
        if (isset($index['quantity_received']) && !empty($index['quantity_received'])) {
            $data['quantity_received'] = $index['quantity_received'];
        } else {
            $data['quantity_received'] = 0;
        }
        if (isset($index['quantity_outstanding']) && !empty($index['quantity_outstanding'])) {
            $data['quantity_outstanding'] = $index['quantity_outstanding'];
        } else {
            $data['quantity_outstanding'] = 0;
        }
        if (isset($index['unit_cost']) && !empty($index['unit_cost'])) {
            $data['unit_cost'] = removeDollarCommaSpace($index['unit_cost']);
        } else {
            $data['unit_cost'] = 0;
        }
        if (isset($index['totalcost']) && !empty($index['totalcost'])) {
            $data['total_cost'] = removeDollarCommaSpace($index['totalcost']);
        } elseif (isset($index['total_cost']) && !empty($index['total_cost'])) {
            $data['total_cost'] = removeDollarCommaSpace($index['total_cost']);
        } else {
            $data['total_cost'] = 0;
        }

        return $data;
    }
    // to validate Excel File
    function validateExcelFile($file_ext)
    {
        $valid = array(
            'xls', 'xlsx' // add your extensions here.
        );
        return in_array($file_ext, $valid) ? true : false;
    }
    //for purchase order verify page

    function verify(Request $request)
    {

        if ($request->ajax()) {
            $data = PurchaseOrder::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        $button = '<a href="' . app('url')->route('purchaseVerify.moveToCore', $data->Vendor_Id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('purchaseVerify.vendors', $data->Vendor_Id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->Vendor_Id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        // return view('verify.index');
        return view('purchaseOrder.poVerify');
    }
    /* Move data to main.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCore($id)
    {
        $data = PurchaseOrder::moveDataToCore($id);
        if (empty($query)) {
            Session::flash('message', 'Purchase order data is successfully moved to core');
            Session::flash('alert-class', 'alert-success ');
            return redirect('purchaseVerify');
        }
    }
    /**
     * Display the Vendors Associated Data.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function AssociatedVendors(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = PurchaseOrder::fetchDetailData($id);
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="' . $data->ordered_on_date . '"  id="' . $data->vendor_id . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('purchaseOrder.poDetailVerify')->with('vendor_id', $id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $date)
    {
        $data = PurchaseOrder::deleteSelectedRecord($id, $date);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /* Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyVendor($id)
    {
        $data = PurchaseOrder::deleteAllRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
}
