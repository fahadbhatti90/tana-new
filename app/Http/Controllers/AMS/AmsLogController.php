<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Ams\Amslogs;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AmsLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Amslogs::fetchData();
            return DataTables::of($data)
                ->make(true);
        }
        return view('ams.amsLogs');
    }
}
