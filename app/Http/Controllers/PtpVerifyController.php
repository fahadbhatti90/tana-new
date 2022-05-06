<?php

namespace App\Http\Controllers;

use App\Model\ptp\PtpVerify;
use App\Model\Vendors;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use Session;

use Illuminate\Http\Request;

class PtpVerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,8')->only(['index', 'AssociatedVendors']);
        $this->middleware('permission:3,8')->only(['moveToCore', 'saleToCore']);
        $this->middleware('permission:4,8')->only(['destroyVendor', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor_list = PtpVerify::vendorsList();
        if ($request->ajax()) {
            $ptpData = PtpVerify::ptpViewTable();
            return DataTables::of($ptpData)->make(true);
        }
        return view('ptp.ptpVerify')->with('vendor_list', $vendor_list);
    }

    /**
     * For calling sp
     *
     * @return \Illuminate\Http\Response
     */
    public function ptpMoveData()
    {
        $query = PtpVerify::ptpView();
        if (empty($query)) {
            Session::flash('message', 'PTP data is successfully moved to core');
            Session::flash('alert-class', 'alert-success ');
            return redirect('verifyPtp');
        } else {
            foreach ($query as $qry) {
                $result = $qry->Level;
            }
            return $result;
        }
    }
    /**
     * For calling delete function
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendorName = $request->input('ptp_filter_vendor');
        if (empty($vendorName)) {
            $errorMessage = array('No Vendor Selected!');
            $responseData = array('error' => $errorMessage, 'ajax_status' => false);
            return response()->json($responseData);
        } else {
            $vendor = PtpVerify::deleteVendor($vendorName);
            $Message = array('Record deleted successfully');
            $responseData = array('success' => $Message, 'ajax_status' => true);
            return response()->json($responseData);
        }
    }
}
