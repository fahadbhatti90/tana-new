<?php

namespace App\Http\Controllers\Traffic;

use App\Http\Controllers\Controller;
use App\Model\Traffic\Traffic;
use App\Model\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Session;
use Helper;
use Carbon\Carbon;
use DB;

class TrafficController extends Controller
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
        return view('traffic.index')->with('vendor_list', $vendor_list);
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
            ['vendor' => 'required', 'trafficFile' => 'required'],
            [
                'trafficFile.required' => 'Traffic file is required',
                'vendor.required' => 'Vendor field is required'
            ]
        );
        $responseData = array();
        if ($validator->passes()) {
            // get Extension
            if ($request->hasFile('trafficFile')) {
                $fileArray = $request->file('trafficFile');
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
                    $fileExtension = ($request->hasFile('trafficFile') ? $file->getClientOriginalExtension() : '');
                    // Validate upload file
                    if ($this->validateExcelFile($fileExtension) != false) {
                        if (!is_null($isAccountAssociated)) {
                            list($start, $TrafficData) = getDataFromExcelFile($file, 'traffic');
                            // check if sales Data not empty
                            if (!empty($TrafficData)) {
                                if (isset($TrafficData[0]['asin']) && isset($TrafficData[0]['product_title']) && isset($TrafficData[0]['subcategory'])) {
                                    $storeTrafficData = []; // define array for Store Data into DB
                                    $dbData = [];
                                    $report_date = $start['startdate'];
                                    foreach ($TrafficData as $data) {
                                        $dbData = $this->TrafficData($data);
                                        $dbData['fk_vendor_id'] = $fkVendorId;
                                        $dbData['report_date'] = $report_date;
                                        $dbData['captured_at'] = date('Y-m-d h:i:s');
                                        array_push($storeTrafficData, $dbData);
                                    }
                                    // End for each Loop
                                    if (!empty($storeTrafficData)) {
                                        foreach (array_chunk($storeTrafficData, 1000) as $t) {
                                            $sales_data = Traffic::Insertion($t);
                                        }
                                    }
                                    unset($salesData);
                                    unset($storeTrafficData);
                                    unset($dbData);
                                    $request->session()->put('fk_vendor_id', $fkVendorId);
                                    $responseData = array('success' => 'You have successfully uploaded Report', 'ajax_status' => true);
                                } else {
                                    $errorMessage = array('Traffic data file is required');
                                    $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                                } // End condition of if else
                            } else {
                                $errorMessage = array('Uploaded file is empty kindly upload updated file!');
                                $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                            } // End condition of if else
                        } else {
                            $errorMessage = array('This account is not associated kindly associate it with any client!');
                            $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                        }
                    } else {
                        $errorMessage = array('File extension should be csv, xls or xlsx');
                        $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                    }
                }
                if ($arrayLen == $z) {
                    $errorMessage = array('Traffic data file field is required');
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
    private function TrafficData($data)
    {
        $dbData = array();
        $dbData['asin'] = (isset($data['asin']) && !empty($data['asin']) ? $data['asin'] : '0');
        $dbData['product_title'] = (isset($data['product_title']) && !empty($data['product_title']) ? $data['product_title'] : '0');
        $dbData['subcategory'] = (isset($data['subcategory']) && !empty($data['subcategory']) ? $data['subcategory'] : '0');
        $dbData['category'] = (isset($data['category']) && !empty($data['category']) ? $data['category'] : '0');
        $dbData['model_number'] = (isset($data['model_number']) && !empty($data['model_number']) ? $data['model_number'] : '0');
        $dbData['glance_views'] = (isset($data['glance_views']) && !empty($data['glance_views']) ? $data['glance_views'] : '0');
        $dbData['glance_views_%_of total'] = (isset($data['glance_views_%_of total']) && !empty($data['glance_views_%_of total']) ? $data['glance_views_%_of total'] : '0');
        $dbData['glance_view_prior_period'] = (isset($data['glance_view_prior_period']) && !empty($data['glance_view_prior_period']) ? $data['glance_view_prior_period'] : '0');
        $dbData['glance_view_last_year'] = (isset($data['glance_view_last_year']) && !empty($data['glance_view_last_year']) ? $data['glance_view_last_year'] : '0');
        $dbData['conversion_rate'] = (isset($data['conversion_rate']) && !empty($data['conversion_rate']) ? $data['conversion_rate'] : '0');
        $dbData['conversion_rate_prior_period'] = (isset($data['conversion_rate_prior_period']) && !empty($data['conversion_rate_prior_period']) ? $data['conversion_rate_prior_period'] : '0');
        $dbData['conversion_rate_last_year'] = (isset($data['conversion_rate_last_year']) && !empty($data['conversion_rate_last_year']) ? $data['conversion_rate_last_year'] : '0');
        $dbData['unique_visitors_prior_period'] = (isset($data['unique_visitors_prior_period']) && !empty($data['unique_visitors_prior_period']) ? $data['unique_visitors_prior_period'] : '0');
        $dbData['unique_visitors_last_year'] = (isset($data['unique_visitors_last_year']) && !empty($data['unique_visitors_last_year']) ? $data['unique_visitors_last_year'] : '0');
        $dbData['fast_track_gv'] = (isset($data['fast_track_gv']) && !empty($data['fast_track_gv']) ? $data['fast_track_gv'] : '0');
        $dbData['fast_track_gv_prior_period'] = (isset($data['fast_track_gv_prior_period']) && !empty($data['fast_track_gv_prior_period']) ? $data['fast_track_gv_prior_period'] : '0');
        $dbData['fast_track_gv_last_year'] = (isset($data['fast_track_gv_last_year']) && !empty($data['fast_track_gv_last_year']) ? $data['fast_track_gv_last_year'] : '0');

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
            $data = Traffic::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('traffic.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('traffic.verify', $data->vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('traffic.verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function moveToCore($id)
    {
        Traffic::moveSelectedDataToCore($id);
        Session::flash('message', 'Traffic data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('traffic/verify_all');
    }

    public function moveAllToCore()
    {
        Traffic::moveDataToCore();
        Session::flash('message', 'Traffic data is saved');
        Session::flash('alert-class', 'alert-success ');
        return redirect('traffic/verify_all');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Traffic::deleteAllRecord($id);
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

        Traffic::deleteSelectedRecord($id, $request['received_date']);
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
            $data = Traffic::fetchDetailData($id);
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if ($data->Duplicate == 'Yes') {
                        $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled" style="display:none;"><i class="feather icon-check-circle"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->Reported_Date . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('traffic.verify')->with('vendor_id', $id);
    }
}
