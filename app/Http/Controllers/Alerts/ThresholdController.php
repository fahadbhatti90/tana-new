<?php

namespace App\Http\Controllers\Alerts;

use App\Http\Controllers\Controller;
use App\Model\Alerts\KpiInfo;
use App\Model\Alerts\KpiThreshold;
use App\Model\Vendors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThresholdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $rules = array(
            'sub_kpi_name' => ['required'],
            'sub_kpi_value' => ['required'],
            'report_name' => ['required'],
            'report_graph' => ['required'],
            'report_range' => ['required', 'int', 'max:4'],
            'report_vendor' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Your threshold are invalid']);
        }

        $range = "";
        switch ($request['report_range']){
            case 1:
                //for daily report
                $range = "daily";
                break;
            case 2:
                //daily report in case of weekly
                $range = "daily";
                break;
            case 3:
                //weekly report in case of monthly
                $range = "weekly";
                break;
            case 4:
                //monthly report in case of yearly
                $range = "monthly";
                break;
        }

        $vendor = 0;
        if($request['report_vendor'] != 0){
            $vendor = Vendors::find($request['report_vendor'])->vendor_id;
        }

        $checkKPI = KpiInfo::where("report_name", $request['report_name'])
                            ->where("sub_kpi_value", $request['sub_kpi_value'])
                            ->where("report_range", $range)
                            ->where("report_graph", $request['report_graph'])
                            ->get()->first();

        if(!isset($checkKPI->report_name)){
            $tempKpi = KpiInfo::where("report_name", $request['report_name'])
                ->where("report_range", $range)
                ->where("report_graph", $request['report_graph'])
                ->groupBy('kpi_name')
                ->get();

            foreach ($tempKpi as $kpiInfo){
                KpiInfo::create([
                    'kpi_name'=> $kpiInfo->kpi_name,
                    'sub_kpi_name'=> $request['sub_kpi_name'],
                    'sub_kpi_value'=> $request['sub_kpi_value'],
                    'report_name'=> $request['report_name'],
                    'report_range'=> $range,
                    'report_graph'=> $request['report_graph'],
                    'report_table'=> $kpiInfo->report_table,
                ]);
            }
        }

        $kpi = KpiInfo::where("report_name", $request['report_name'])
            ->where("sub_kpi_value", $request['sub_kpi_value'])
            ->where("report_range", $range)
            ->where("report_graph", $request['report_graph'])
            ->get();

        KpiInfo::where("report_name", $request['report_name'])
                ->where("report_range", $range)
                ->where("report_graph", $request['report_graph'])->get();

        $i = 0;
        $kpi_data = array();
        foreach ($kpi as $kpiInfo){
            $kpi_data[$i]['kpi_id'] = $kpiInfo->kpi_id;
            $kpi_data[$i]['kpi_name'] = $kpiInfo->kpi_name;
            $i++;
        }

        $data = array();
        $i = 0;

        foreach ($kpi as $kpiInfo){

            $thresholds = KpiThreshold::where('fk_kpi_id', $kpiInfo->kpi_id)
                            ->where('fk_user_id', auth()->user()->user_id)
                            ->where('fk_vendor_id',$vendor)->get();

            foreach($thresholds as $threshold ){
                if(isset($threshold->threshold_type)){
                    $data[$i]['threshold_id'] = $threshold->threshold_id;
                    $data[$i]['kpi_id'] = $kpiInfo->kpi_id;
                    $data[$i]['kpi_name'] = $kpiInfo->kpi_name;
                    $data[$i]['threshold_type'] = $threshold->threshold_type;
                    $data[$i]['threshold_value'] = $threshold->threshold_value;
                    $i++;
                }
            }

        }

        return response()->json(['kpi' => $kpi_data, 'threshold' => $data, 'vendor' => $vendor ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'fk_vendor_id' => ['required', 'int'],
            'kpi_id' => ['required', 'array'],
            'threshold_range' => ['required', 'array'],
            'threshold_value' => ['required', 'array'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Your alert rule records are invalid']);
        }

        foreach ($request['kpi_id'] as $kpi_key => $kpi_value ){

            $thresholdexist = KpiThreshold::where('fk_user_id', auth()->user()->user_id)
                                        ->where('fk_vendor_id', $request['fk_vendor_id'])
                                        ->where('fk_kpi_id', $request['kpi_id'][$kpi_key])
                                        ->where('threshold_type', $request['threshold_range'][$kpi_key])
                                        ->first();

            if(isset($request['threshold_value'][$kpi_key])) {
                if ($thresholdexist == null) {
                    KpiThreshold::create([
                        'fk_user_id' => auth()->user()->user_id,
                        'fk_vendor_id' => $request['fk_vendor_id'],
                        'fk_kpi_id' => $request['kpi_id'][$kpi_key],
                        'threshold_type' => $request['threshold_range'][$kpi_key],
                        'threshold_value' => $request['threshold_value'][$kpi_key]
                    ]);
                } else {
                    KpiThreshold::where('fk_user_id', auth()->user()->user_id)
                        ->where('fk_vendor_id', $request['fk_vendor_id'])
                        ->where('fk_kpi_id', $request['kpi_id'][$kpi_key])
                        ->where('threshold_type', $request['threshold_range'][$kpi_key])
                        ->update([
                            'threshold_value' =>  $request['threshold_value'][$kpi_key]
                        ]);
                }
            }
        }
        return response()->json(['success' => 'Alerts rules are successfully saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $threshold = KpiThreshold::findorfail($id);
        if(isset($threshold->threshold_value)) {
            $threshold->delete();
            return response()->json(['success' => 'Alert rule successfully removed']);
        }
        return response()->json(['errors' => 'Your alert rule record is invalid']);
    }
}
