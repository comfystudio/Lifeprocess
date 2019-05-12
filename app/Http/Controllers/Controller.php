<?php


namespace App\Http\Controllers;

use App\Models\Coach;
use AppHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Message;
use Illuminate\Routing\Controller as BaseController;
use Session;
use Cache;
use App\Models\User;
use App\Models\Agent;


class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct() {
        //middleware to pass the theme based on maintenance mode .. or request...
        $this->user = null;
        $this->middleware(function ($request, $next) use(&$theme) {
            $this->user = Auth::user();

            //We need to check if user is agent and if so do they have premission to proceed based on need_card and card number
            if (Auth::user()->user_type == 'agent' && !$request->is('agent/add-card')) {
                $user = User::where('id', '=', Auth::user()->id)->with('agent')->first();
                if ($user->agent->need_card == 1 && $user->agent->card_number == null) {
                    return redirect('agent/add-card');
                }
            }

            //we need to check if user is an agent or user of an agent so we can change the colours / logo
            if (Auth::user()->user_type == 'agent') {
                $agent = Agent::where('user_id', '=', Auth::user()->id)->first();
                if($agent->count()){
                    view()->share("agent_theme", $agent);
                }
            }else{
                //if current user was created by another user
                if(Auth::user()->created_by != 0){
                    //need to find out who created current user.
                    $creator = User::where('id', '=', Auth::user()->created_by)->first();
                    //if creator is an agent
                    if($creator['role_id'] == 5){
                        //get the agent theme data and add it to the layout
                        $agent = Agent::where('user_id', '=', Auth::user()->created_by)->first();
                        if($agent->count()){
                            view()->share("agent_theme", $agent);
                        }
                    }
                }
            }

            //For side menu we need a way to work out if current user is coached by LLP coach or client manager
            $user = User::where('id', '=', Auth::user()->id)->with('client')->first();
//            if(isset($user['client']) && !empty($user['client']['coach_id'])){
            if(isset($user['client'])){
                view()->share("user_credits", $user['client']['credits']);
                //now we have coach id we need to drill down to find if they belong to client manager
                $creator = Coach::where('id', '=', $user['client']['coach_id'])->with('user')->first();
                if(isset($creator['user']) && !empty($creator['user']['created_by'])) {
                    $creator = User::where('id', '=', $creator['user']['created_by'])->first();
                    //if creator is an agent
                    if($creator['role_id'] == 5){
                        view()->share("llp_coach", 0);
                    }else{
                        view()->share("llp_coach", 1);
                    }
                }else{
                    view()->share("llp_coach", 1);
                }
            }else{
                view()->share("llp_coach", 1);
            }

            $maintenance_mode = 'Off';
            $maintenance_mode_message = '';
            if(isset(Cache::get('settings')['maintenance_mode'])) {
                $maintenance_mode = Cache::get('settings')['maintenance_mode'];
            }
            if(isset(Cache::get('settings')['maintenance_mode_message'])) {
                $maintenance_mode_message = Cache::get('settings')['maintenance_mode_message'];
            }
            if(Auth::user()->user_type == 'client') {
                $theme = 'limitless.client-layout';
                //$theme = 'limitless.layout';
            } else {
                $theme = 'limitless.layout';
            }
            if(Auth::check()) {
                if($maintenance_mode == 'On') {
                    $user = Auth::user();
                    $userTypes_arr = ['coach', 'client', 'agent'];
                    if(in_array($user->user_type, $userTypes_arr)) {
                        view()->share('maintenance_mode_message', $maintenance_mode_message);
                        $theme = 'limitless.maintenance-mode-on';
                        // add it to the request
                        session()->push('theme', $theme);
                        // view()->share('maintenance_theme', 'limitless.maintenance-mode-on');
                    }
                }
            }
            view()->share("theme", $theme);
            if (request()->input('download', false)) {
                view()->share("theme", 'limitless.ajax');
            }
            if(Auth::check()) {
                // set current user's timezone and make it remember to cache...
                $current_user_timeZone = Cache::remember('current_user_timeZone', 3600, function() {
                    return Auth::user()->timezone;
                });

                \Config::set('srtpl.current_user_timeZone', $current_user_timeZone);
            }
            // $counter = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('is_read', '=', 0)->get()->count();
            $msg = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('is_read', '=', 0)->get();
            //echo Auth::user()->id;
            $counter1=0;
            foreach($msg as $val)
            {
                $user=User::where('id',$val->create_user_id)->get();
                 $counter=0;
                foreach($user as $user)
                {
                    $counter=0;
                    if($user->deleted=='0' && $user->status=='active')
                    {
                        $counter = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('create_user_id',$user->id)->where('is_read', '=', 0)->get()->count();
                    }

                }
                $counter1+=$counter;
                //dump($counter1);
            }
            if(Auth::user()->user_type=='client')
            {
                $counter1=0;
                $msg = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('is_read', '=', 0)->get()->count();
                $counter1=$msg;

                $msg = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('create_user_id','1')->where('is_read', '=', 0)->get()->count();
                $admin_counter=$msg;

                $counter1=$counter1-$msg;
                view()->share("unread_admin_counter", $admin_counter);

            }

            if(Auth::user()->user_type=='coach')
            {
                $counter1+=0;
            }
            else
            {
                if(Auth::user()->user_type!='user')
                {
                    if(Auth::user()->is_read_welcome_msg==0)
                    {
                        $counter1+=1;
                    }
                }
            }
            view()->share("unread_counter", $counter1);
            //dump($counter1);
            return $next($request);
        });

        AppHelper::setDefaultImage("uploads/default/default.jpg");

        \DB::connection()->enableQueryLog();
	}

    public function addMailChimpUser($user){
        $apikey = config('app.mailChimpApiKey');
        $apiEndPoint = explode('-', $apikey);
        $list_id = config('app.mailChimpListId');

        $email = $user->email;
        $fname = $user->first_name;
        $lname = $user->last_name;

        $auth = base64_encode( 'user:'.$apikey );
        $data = array(
            'apikey'        => $apikey,
            'email_address' => $email,
            'status' => "subscribed",
            'tags' => array('LPP Clients'),
            'merge_fields'  => array(
                'FNAME' => $fname,
                'LNAME' => $lname,
            )
        );
        $json_data = json_encode($data);

        $ch = curl_init();

        $curlopt_url = "https://".$apiEndPoint[1].".api.mailchimp.com/3.0/lists/$list_id/members/";
        curl_setopt($ch, CURLOPT_URL, $curlopt_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $result = curl_exec($ch);
        curl_close($ch);
        $myArray = json_decode($result, true);
        if (!empty($myArray->error)) return "Mailchimp Error: ".$myArray->error;

    }

    public function deleteMailChimpUser($user){
        $apikey = config('app.mailChimpApiKey');
        $apiEndPoint = explode('-', $apikey);
        $list_id = config('app.mailChimpListId');

        $email = $user->email;
        $fname = $user->first_name;
        $lname = $user->last_name;
        $subscriber_hash = md5(strtolower($email));

        $auth = base64_encode( 'user:'.$apikey );
        $data = array(
            'apikey'        => $apikey,
            'email_address' => $email,
            'status' => "subscribed",
            'tags' => array('LPP Clients'),
            'merge_fields'  => array(
                'FNAME' => $fname,
                'LNAME' => $lname,
            )
        );
        $json_data = json_encode($data);

        $ch = curl_init();

        $curlopt_url = "https://".$apiEndPoint[1].".api.mailchimp.com/3.0/lists/".$list_id."/members/".$subscriber_hash."/actions/delete-permanent";



        curl_setopt($ch, CURLOPT_URL, $curlopt_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $result = curl_exec($ch);
        curl_close($ch);
        $myArray = json_decode($result, true);
        if (!empty($myArray->error)) return "Mailchimp Error: ".$myArray->error;

    }


}
