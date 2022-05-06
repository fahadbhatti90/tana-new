<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Model\Process\Process;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProcessController extends Controller
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
            $data = Process::fetchData();
            return DataTables::of($data)
                ->make(true);
        }
        return view('process.index');
    }
}
