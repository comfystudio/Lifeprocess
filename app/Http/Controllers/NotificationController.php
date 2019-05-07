<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotifyUser;
use Flash;
use Auth;
use DB;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->title = "Alerts";
        view()->share('title', $this->title);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        view()->share('notifications', $this->get_index());
        $this->readNotifications();
        // dump($this->getUnreadNotificatoinCounter());
        return view('notifications.index');
    }

    public function get_alerts (){
       view()->share('notifications', $this->get_index());
        $this->readNotifications();
        // dump($this->getUnreadNotificatoinCounter());
        return view('messages.coach.system_alerts');
    }

    //Get all notifications to the coach sent by the client.
    public function get_index($filter = array())
    {
        $notifications = NotifyUser::with(['notification'])
            ->where('receiver_id', Auth::id())
            ->orderBy('created_at', 'DESC')->get();
        return $notifications;
    }

    public function getUnreadNotificatoinCounter()
    {
        $notifications = NotifyUser::with(['notification'])
            ->where('receiver_id', Auth::id())
            ->where('read', DB::Raw('"0"'))->count();
        return $notifications;
    }

    public function readNotifications()
    {
        // update read counter...
        NotifyUser::where('receiver_id', Auth::id())->update(['read' => '1']);
    }
}
