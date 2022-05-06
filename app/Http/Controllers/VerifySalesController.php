<?php

namespace App\Http\Controllers;

use App\Model\Vendors;
use App\Model\VerifySales;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use Illuminate\Http\Request;
use Session;



class VerifySalesController extends Controller
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
        if ($request->ajax()) {
            $data = VerifySales::fetchData();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 3)) {
                        if ($data->Duplicate == 'Yes') {
                            $button = '<a href="#" id="anchor" name="anchor" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light" disabled="disabled"><i class="feather icon-check-circle"></i> </a>';
                        } else {
                            $button = '<a href="' . app('url')->route('verify.moveToCore', $data->vendor_id, true) . '" title="Save" class="edit btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-check-circle"></i> </a>';
                        }
                    }
                    if (checkOptionPermission(array(8), 1)) {
                        $button .= ' <a href="' . app('url')->route('verify.vendors', $data->vendor_id, true) . '" title="Show Records" class="auth btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-info"></i> </a>';
                    }
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="removeVendor"  id="' . $data->vendor_id . '" title="Delete Records" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('verify.index');
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
        $data = VerifySales::fetchDetailData($id);
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = "";
                    if (checkOptionPermission(array(8), 4)) {
                        $button .= ' <button type="button"   name="' . $data->SaleDate . '"  id="' . $data->vendor_id . '" title="Delete Record" class="removeVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('verify.vendors')->with('vendor_id', $id);
    }
    /* Move data to main.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCore($id)
    {
        $data = VerifySales::moveDataToCore($id);
        if (empty($query)) {
            Session::flash('message', 'Sales data is successfully moved to core');
            Session::flash('alert-class', 'alert-success ');
            return redirect('verify');
        }
    }
    /* Move all data to main.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saleToCore()
    {
        $data = VerifySales::moveSelectedDataToCore();
        if (empty($query)) {
            Session::flash('message', 'Sales data is successfully moved to core');
            Session::flash('alert-class', 'alert-success ');
            return redirect('verify');
        }
    }
    /* Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyVendor($id)
    {
        $data = VerifySales::deleteAllRecord($id);
        return response()->json(['success' => 'Record deleted successfully']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $date)
    {
        $data = VerifySales::deleteSelectedRecord($id, $date);
        return response()->json(['success' => 'Record deleted successfully']);
    }
}
