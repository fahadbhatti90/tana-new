<?php

namespace App\Http\Controllers;

use App\Model\PtpSale;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use Illuminate\Http\Request;

class DetailSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,10')->only(['index']);
        $this->middleware('permission:2,10')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ptp.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //to seperate input type hidden field values
        $validator = Validator::make($request->all(), ['ptpfile' => 'required']);
        $responseData = array();
        $errorMessage = array();
        $successMessage = array();
        if ($validator->passes()) {
            if ($request->hasFile('ptpfile')) {
                $file_array = $request->file('ptpfile');
                $fileExtension = ($request->hasFile('ptpfile') ? $request->file('ptpfile')->getClientOriginalExtension() : '');
                // Validate upload file
                if ($this->validateExcelFile($fileExtension) != false) {
                    $ptpExcelData = getPtpDataFromExcelFile($request->file('ptpfile'), 'detailsales');
                    if ($ptpExcelData == true) {
                        unset($ptpExcelData);
                        // $request->session()->put('fk_vendor_id');
                        $responseData = array('success' => 'You have successfully uploaded Report!', 'ajax_status' => true);
                    } else {
                        $errorMessage = array('File is not valid kindly upload ptp file!');
                        $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                    } // End condition of if else
                } else {
                    $errorMessage = array('File Extension should csv, xls, xlsx');
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
     * For excel file validation.
     *
     */
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
