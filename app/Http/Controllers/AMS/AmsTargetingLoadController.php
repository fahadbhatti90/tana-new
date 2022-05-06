<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Model\Ams\AmsTargetLoad;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmsTargetingLoadController extends Controller
{

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('ams.amsVerify.targetLoad');
    }

    /**
     * Load Daily Target record
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyTargeting(Request $request)
    {
        //call Model Static Function for Calling Store Procedure
        $dailyTargetResponse = AmsTargetLoad::loadDailyTarget();

        return response()->json([
            'success' => 'Target records are successfully loaded',
            'response' => $dailyTargetResponse
        ]);
    }

    /**
     * Load Daily Keyword record
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyKeyword(Request $request)
    {
        //call Model Static Function for Calling Store Procedure
        $dailyCampaignResponse = AmsTargetLoad::loadDailyKeyword();

        return response()->json([
            'success' => 'Keyword records are successfully loaded',
            'response' => $dailyCampaignResponse
        ]);
    }
}
