<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use App\Models\ReferFriend;
use App\Models\User;
use Auth;
use DB;

class ReferFriendsReportController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:report.refer_friend_report.view', ['only' => ['index']]);
        $this->title = "Refer Friends Report";
        view()->share('title', $this->title);
    }
    public function index(Request $request) {
        $timezone = Auth::user()->timezone;
        view()->share('title', $this->title);
        $report_data = $this->get_index(array());
        view()->share('timezone', $timezone);
        view()->share('count','1');
        view()->share('ReferFriendReport', $report_data);
        return view('referfriend_report.index');
    }
    public function get_index($filters = array(), $sort_order = array()) {
        $id = Auth::id();
        $models = ReferFriend::with(['user']);
        if (request()->get('group_by') == 'user' ) {
            $models->groupBy('create_user_id')->select('refer_friends.*',DB::raw('count(id) as total'));
        }
        else if (request()->get('group_by') == 'role') {
            $models = User::where('deleted', '0')
                    ->select('user_type', DB::raw('count(refer_friends.create_user_id) as total_refer_friend'))
                    ->Leftjoin('refer_friends','users.id','=','refer_friends.create_user_id')->groupBy('user_type');
        }
        if (request()->get('from_date', false) && request()->get('from_date') != '__/__/____') {
                $models->where(\DB::raw("DATE(created_at)"), '>=', Carbon::createFromFormat('m/d/Y', request()->get("from_date"))->format('Y-m-d'));
        }
        if (request()->get('to_date', false) && request()->get('to_date') != '__/__/____') {
                $models->where(\DB::raw("DATE(created_at)"), '<=', Carbon::createFromFormat('m/d/Y', request()->get("to_date"))->format('Y-m-d'));
        }
        if (!empty($sort_order) && is_array($sort_order)) {
            foreach ($sort_order as $column => $direction) {
                $models->orderBy($column, $direction);
            }
        } else {
            $models->orderBy('refer_friends.id', 'DESC');
        }
        return $models->get();
        
    }
    public function getPDF() {
        $report_data = $this->get_index(array());
        $timezone = Auth::user()->timezone;
        $pdf = PDF::loadView('referfriend_report.pdf', ['ReferFriendReport' => $report_data, 'count' => '1', 'theme' => 'limitless.pdf', 'timezone' => $timezone]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('header-right', '');
        return $pdf->stream();
    }
    public function isReferFriendEmail($email)
    {
        $friend_mail = ReferFriend::where('friends_email','nishant.kotak+8@sphererays.net')->first();

    }
    
}
