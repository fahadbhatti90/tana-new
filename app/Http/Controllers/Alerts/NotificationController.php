<?php

namespace App\Http\Controllers\Alerts;

use App\Http\Controllers\Controller;
use App\Model\Alerts\Notification;
use App\Model\Vendors;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Auth::user()->getAllNotification();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('vendor', function($data){
                    return Vendors::where('vendor_id', $data->fk_vendor_id)->pluck('vendor_name')->all();
                })
                ->addColumn('action', function($data){
                    $button = ' <a href="' . app('url')->route('notification.show', $data->alert_id, true) . '" title="view Notification" class="view btn-icon btn btn-info btn-round btn-sm waves-effect waves-light"><i class="feather icon-eye"></i> </a>';
                    if( $data->is_viewed == 1){
                        $button = ' <a href="' . app('url')->route('notification.show', $data->alert_id, true) . '" title="view unreviewed Notification" class="view btn-icon btn btn-success btn-round btn-sm waves-effect waves-light"><i class="feather icon-bell"></i> </a>';
                    }
                    $button .= ' <button type="button"  name="disable" id="' . $data->alert_id . '" title="Remove Notification" class="disable btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-x"></i> </button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('notification.index');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function getNewNotification(Request $request)
    {
        return Auth::user()->getNewNotification();
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */
    public function markAllAsRead()
    {
        //change is_notified status before notifying all new notifications
        Notification::where('fk_user_id', Auth::user()->user_id)
                            ->update(array(
                                'is_notified' => '0',
                                'is_viewed' => '0'
                            ));
        return response()->json(['success' => 'All notifications are marked as read']);
    }

    /**
     * Show the application dashboard.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        //change is_notified status before notifying all new notifications
        Notification::where('fk_user_id', Auth::user()->user_id)
            ->where('alert_id', $id)
            ->update(array(
                'is_notified' => '0',
                'is_viewed' => '0'
            ));

        $notification = Auth::user()->getAllNotification()
            ->where('alert_id', $id)->first();

        $vendor = Vendors::where('vendor_id', $notification->fk_vendor_id)->pluck('vendor_name')->all();

        return view('notification.show')
                                ->with('notification', $notification)
                                ->with('vendor', $vendor[0]);
    }

    /**
     * Show the application dashboard.
     *
     * @param $id
     * @return JsonResponse
     */
    public function disable($id)
    {
        //change is_notified status before notifying all new notifications
        Notification::where('fk_user_id', Auth::user()->user_id)
            ->where('alert_id', $id)
            ->update(array(
                'is_notified' => '0',
                'is_viewed' => '0',
                'is_disabled' => '0'
            ));
        return response()->json(['success' => 'Notifications is successfully removed']);
    }
}
