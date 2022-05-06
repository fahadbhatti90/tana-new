<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Model\VerifyCategory;
use App\Model\Vendors;
use Helper;
use Session;
use DB;

class CategoryVerifyController extends Controller
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
        $vendor_list = VerifyCategory::vendorsList();
        if ($request->ajax()) {
            $CategoryData = VerifyCategory::categoryViewTable();
            return DataTables::of($CategoryData)->make(true);
        }
        return view('category.categoryVerify')->with('vendor_list', $vendor_list);
    }

    /**
     * For calling sp
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryMoveData()
    {
        $query = VerifyCategory::categoryViewSp();
        if (empty($query)) {
            Session::flash('message', 'Category data is successfully moved to core');
            Session::flash('alert-class', 'alert-success ');
            return redirect('verifyCategory');
        }
    }
    /**
     * For calling delete function
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendorName = $request->input('category_filter_vendor');
        if (empty($vendorName)) {
            $errorMessage = array('No Vendor Selected!');
            $responseData = array('error' => $errorMessage, 'ajax_status' => false);
            return response()->json($responseData);
        } else {
            $vendor = VerifyCategory::deleteCategoryVendor($vendorName);
            $Message = array('Record deleted successfully');
            $responseData = array('success' => $Message, 'ajax_status' => true);
            return response()->json($responseData);
        }
    }
}
