<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Client;
use App\Models\Coach;
use App\Models\EmailTemplate;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Mail;
use Cache;
use Fused\Zoom\Zoom;
use App\Models\CoachSceduleBooked;
use Carbon\Carbon;
use App\Models\CoachSchedule;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:messages.create', ['only' => ['create', 'store']]);
        $this->middleware('check_for_permission.access:messages.view', ['only' => ['index', 'show']]);
        $this->middleware('check_for_permission.access:messages.update', ['only' => ['edit', 'update']]);
        $this->middleware('check_for_permission.access:messages.delete', ['only' => ['destroy']]);
        $this->title = "Message";
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function isOnline($id)
    {
        $total=Message::where('create_user_id',$id)->where('receive_user_id','1')->where('is_read','0')->get()->count();
        if($total>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function index(Request $request)
    {
        $user_type = Auth::user()->user_type;
        $user_id   = Auth::id();
        $timezone  = Auth::user()->timezone;
        $messages  = $this->get_messages($user_id);
        $user = Auth::user();
        view()->share('messages', $messages);
        view()->share('timezone', $timezone);
        $search = $request->get('search', false);
        $client = Client::with('program')->where('user_id',$user->id)->first();
        view()->share('client',$client);
        view()->share('timezones', get_timezone_list());

        if ($user_type == "coach" || $user_type == "agent") {
            // $admin = User::with(['send', 'receive'])->where('user_type', '=', 'user');
            // if ($search) {
            //     $admin = $admin->where('users.name', 'like', '%' . $search . '%');
            // }
            // $admin = $admin->get(['id', 'name','image','is_login']);
            // //dd($admin[0]['id']);
            // $admin = $this->getSortUserList($admin);
            // $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $admin[0]['id'])->update(['is_read' => 1]);
            // view()->share('admin', $admin);
            // view()->share('user_type', $user_type);
            $default_message = Coach::with('clients')->where('user_id',$this->user->id)->first();

            $timezone  = Auth::user()->timezone;
            $user_id   = Auth::id();
            $all_admin = User::where('user_type', 'user')->pluck('id')->toArray();
            //dd($all_admin);
            if (isset($all_admin) && count($all_admin) > 0) {
                $client_messages = Message::whereIn('receive_user_id', $all_admin)->where('create_user_id', $user_id)->get();
                $admin_messages  = Message::whereIn('create_user_id', $all_admin)->where('receive_user_id', $user_id)->get();
                $messages        = $client_messages->merge($admin_messages);
                $messages        = $messages->unique('messages')->sortBy('id');
                $updated = Message::where('receive_user_id', '=', $user_id)->whereIn('create_user_id', $all_admin)->update(['is_read' => 1]);
                view()->share('messages', $messages);
                view()->share('timezone', $timezone);
                view()->share('user_type', $user_type);
                view()->share('default_message', $default_message);
               return view('messages.comman-panel');
        }
        } elseif ($user_type == 'user') {

            $all_coach = User::with(['send', 'receive'])->where("users.deleted", "0")
                ->where('status', 'active')
                ->where('id', '!=', $user_id)
                ->where('user_type', '!=', 'agent')
                ->where('user_type', '!=', 'client')
                ->where('user_type', '!=', 'user');
            if ($search) {
                $all_coach = $all_coach->where('users.name', 'like', '%' . $search . '%');
            }
            $all_coach = $all_coach->get(['id', 'name','image','is_login']);

            // foreach ($all_coach as $coach) {
            //     $online_user = $this->isOnline($coach['id']);
            // }
            $all_coach = $this->getSortUserList($all_coach);
            //$online_user = $this->isOnline($all_coach[0]['id']);
            //dd($online_user);
            //view()->share('online_user',$online_user);

            view()->share('all_coach', $all_coach);
            if(!empty($allcoach))
            {
            $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $all_coach[0]['id'])->update(['is_read' => 1]);
            }
            return view('messages.admin.alluser');
        } elseif ($user_type == "client") {

            $client = Client::where("clients.deleted", "0")->where('user_id', '=', $user_id)->with('coach.user','program');

            if ($search) {
                $client = $client->where('users.name', 'like', '%' . $search . '%');
            }
            $user = Auth::user();
            //$client = Client::with('program')->where('user_id','=',$user_id)->first();
            $client = $client->first();
            if (!$client->coach_id) {
                view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
                 return view('client-dashboard');
            }
            //echo $client->coach_id;exit;
            view()->share('client', $client);
            //echo $client->coach_id;exit;
            //dd($client->coach->user_id);
            $user=User::where('id', '=', $user_id)->get();

            $update=User::where('id', $user_id)->update(['is_read_welcome_msg' => '1']);
            //dd($update);
            $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $client->coach->user_id)->update(['is_read' => 1]);
            if ($client->coach_id > 0) {
                view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
                return view('messages.client.client-panel');
            } else {
                return view('messages.client.client-admin-panel');
            }
        }
    }
    public function myCoach(){
        $user_type = Auth::user()->user_type;
        $user_id   = Auth::id();
        $timezone  = Auth::user()->timezone;
        $messages  = $this->get_messages($user_id);
        view()->share('messages', $messages);
        view()->share('timezone', $timezone);
        $client = Client::where("clients.deleted", "0")->where('user_id', '=', $user_id)->with('coach.user');

            $client = $client->first();
            //echo $client->coach_id;exit;
            view()->share('client', $client);
            if ($client->coach_id > 0) {

                view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');

                return view('clients.mycoach');
            }
            else
            {

                view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
                 return view('client-dashboard');
            }
             /*else {
                return view('messages.client.client-admin-panel');
            }*/
    }
    public function get_roles($role, Request $request)
    {
        $user_id  = Auth::id();
        $timezone = Auth::user()->timezone;
        $messages = $this->get_messages($user_id);
        view()->share('messages', $messages);
        view()->share('timezone', $timezone);
        $search = $request->get('search', false);
        $where = ['0' => ['users.deleted', '=', '0']];
        if ($request->get('search', false)) {
            $where['1'] = ['users.name', 'like', '%' . $request->get('search', false) . '%'];
        }
        if ($role == 'client') {
            $all_client = User::with(['send', 'receive'])
                ->where($where)
                ->where('user_type', 'client')
                ->where('status','active')
                ->where('deleted','0')
                ->get(['id', 'name','image','is_login']);
            $all_client = $this->getSortUserList($all_client);
            view()->share('all_client', $all_client);
            if(!empty($all_client))
            {
            $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $all_client[0]['id'])->update(['is_read' => 1]);
            }
            return view('messages.admin.allclient');
        }
        if ($role == 'agent') {
            $all_agent = User::with(['send', 'receive'])->where($where)->where('user_type', 'agent')
            	->get();
            $all_agent = $this->getSortUserList($all_agent);
            view()->share('all_agent', $all_agent);
            if(!empty($all_agent))
            {
            $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $all_agent[0]['id'])->update(['is_read' => 1]);
            }
            return view('messages.admin.allagent');
        }
        if ($role == 'coach-client') {

            $counter = Message::where("messages.deleted", "0")->where('receive_user_id', '=', $user_id)->where('is_read', '=', 0)->get()->count();
            //echo $counter;exit;

            $coach = Coach::where("coaches.deleted", "0")
                ->where('user_id', '=', $user_id)
                ->with(['user','clients.user.send','clients.user.receive','clients.user' => function($query) use($search){
                    if($search){
                        $query->where('name', 'like', '%'.$search.'%');

                        //$query->getSortUserList($clients->user);
                    }
                    $query->where('status','active');
                }])
                ->first();
            $users = [];
            foreach ($coach->clients as $client) {
                if($client->user){
                    $users[] = $client->user;
                }
            }

            $users =  collect($users);
            $users = $this->getSortUserList($users,true);
            //dd($users[0]['id']);
            if(!empty($users)){
            $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $users[0]['id'])->update(['is_read' => 1]);
            }
            view()->share('coach_client', $coach->clients);
            view()->share('users', $users);
            view()->share('counter',$counter);

            return view('messages.coach.allclient');
        }
        if ($role == 'client-coach') {
            $client = Client::where("clients.deleted", "0")->where('user_id', '=', $user_id)->with('coach.user')->first();
            view()->share('client', $client);
            //$updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $client['id'])->update(['is_read' => 1]);
            return view('messages.client.client-panel');
        }
        if ($role == 'read-only-coach'){
            $counter = Message::where("messages.deleted", "0")->where('receive_user_id', '=', $user_id)->where('is_read', '=', 0)->get()->count();
            //echo $counter;exit;

            //Need to get list of coach ids based on read-noly-coaches users.
            $ro_users = Client::where('deleted', '=', '0')->where('invite_coach', '=', Auth::user()->email)->pluck('coach_id')->toArray();

            $coach = Coach::where("coaches.deleted", "0")
                ->whereIn('id', $ro_users)
                ->with(['user','clients.user.send','clients.user.receive','clients.user' => function($query) use($search){
                    if($search){
                        $query->where('name', 'like', '%'.$search.'%');

                        //$query->getSortUserList($clients->user);
                    }
                    $query->where('status','active');
                }])
                ->first();
            $users = [];

            foreach ($coach->clients as $client) {
                if($client->user){
                    $users[] = $client->user;
                }
            }

            $users =  collect($users);
            $users = $this->getSortUserList($users,true);
            //dd($users[0]['id']);
//            if(!empty($users)){
//                $updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $users[0]['id'])->update(['is_read' => 1]);
//            }
            view()->share('coach_client', $coach->clients);
            view()->share('users', $users);
            view()->share('counter',$counter);

            return view('messages.coach.allclient');
        }
    }
    public function admin_data($role, $id, Request $request)
    {

        $id        = Crypt::decryptString($id);
        $user_name = User::where('id', $id)->first()->name;
        $user_image = User::where('id', $id)->first()->image;
        $user_type=User::where('id',$id)->first()->user_type;
        //echo $user_image;exit;
        $timezone  = Auth::user()->timezone;
        $user_id   = Auth::id();
        $messages  = $this->get_messages($user_id);
        $search = $request->get('search', false);
        view()->share('messages', $messages);
        view()->share('timezone', $timezone);
        view()->share('user_name', $user_name);
        if ($role == "client")
        {

            $all_client = User::with(['send', 'receive'])->where("users.deleted", "0")->where('user_type', 'client')->where('status','active')->where('deleted','0')->get();
            $all_client = $this->getSortUserList($all_client, false);
            $updated = Message::where('receive_user_id', '=', Auth::id())->where('create_user_id', '=', $id)->where('is_read','0')->count();
            if($updated>0)
            {
                $counter1=0;
                $msg = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('is_read', '=', 0)->get();
                foreach($msg as $val)
                {
                    $user=User::where('id',$val->create_user_id)->get();
                    $counter=0;
                    foreach($user as $user)
                    {
                        if($user->deleted=='0' && $user->status=='active')
                        {
                            $counter = Message::where("messages.deleted", "0")->where('receive_user_id', '=', Auth::user()->id)->where('create_user_id',$id)->where('is_read', '=', 0)->get()->count();
                        }

                    }
                    $counter1+=$counter;
                }
                $counter1=$counter1-$updated;
                view()->share("unread_counter", $counter1);
            }
            view()->share('all_client', $all_client);
            return view('messages.admin.admin-client', ['id' => Crypt::encryptString($id)]);
        } elseif ($role == "coach") {
            $all_coach = User::with(['send','receive'])->where("users.deleted", "0")->where('status','active')->where('user_type', 'coach')->get();
            $all_coach = $this->getSortUserList($all_coach, false);
            view()->share('all_coach', $all_coach);
            if($user_type=='client')
            {
                $all_client = User::with(['send', 'receive'])->where("users.deleted", "0")->where('user_type', 'client')->where('id',$id)->get();
                view()->share('all_client', $all_client);
                return view('messages.admin.admin-client', ['id' => Crypt::encryptString($id)]);
            }
            //$updated = Message::where('receive_user_id', '=', $user_id)->where('create_user_id', '=', $all_coach['id'])->update(['is_read' => 1]);
            return view('messages.admin.admin-coach', ['id' => Crypt::encryptString($id)]);
        } elseif ($role == "agent") {
            $all_agent = User::where("users.deleted", "0")->where('user_type', 'agent')->get(['id', 'name','image','is_login']);
            $all_agent = $this->getSortUserList($all_agent, false);
            view()->share('all_agent', $all_agent);
            return view('messages.admin.admin-agent', ['id' => Crypt::encryptString($id)]);
        }
        if ($role == "coach-client") {
            $coach = Coach::where("coaches.deleted", "0")->where('user_id', '=', $user_id)
                ->with(['clients.user' => function($query) use($search){
                    if($search){
                        $query->where('name', 'like', '%'.$search.'%');
                    }
                }])
                ->first();
            $users = [];
            foreach ($coach->clients as $client) {
                if($client->user){
                    $users[] = $client->user;
                }
            }
            $users =  collect($users);
            $users = $this->getSortUserList($users,true);
            view()->share('coach_client', $coach->clients);
            view()->share('users', $users);
            return view('messages.coach.coach-client', ['id' => Crypt::encryptString($id)]);
        }
        if ($role == "admin") {
            $user_type = Auth::user()->user_type;
            $admin     = User::where('user_type', '=', 'user')->get(['id', 'name','image','is_login'])->toArray();
            //$admin = $this->getSortUserList($admin,true);
            view()->share('admin', $admin);
            view()->share('user_type', $user_type);
            return view('messages.all-admin', ['id' => Crypt::encryptString($id)]);
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $id        = Crypt::decryptString($id);
        $user_id   = Auth::id();
        $user_type = Auth::user()->user_type;

        $input     = [
            'create_user_id'  => $user_id,
            'receive_user_id' => $id,
            'messages'        => $request->get('messages'),
        ];
        $file['attachment'] = '';
        $result             = $this->validate($request, [
            'messages'   => "required",

        ],
            [
                'messages.required' => 'The Message field is required.',

            ]
        );
        if ($request->hasFile('attachment')) {
            $file['attachment'] = \AppHelper::getUniqueFilename($request->file('attachment'), AppHelper::path('uploads/messages/attachment/')->getImagePath());
            $request->file('attachment')->move(AppHelper::path('uploads/messages/attachment/')->getImagePath(), $file['attachment']);
        }
        $model      = Message::create($input);
        $attachment =
            [
            'message_id' => $model->id,
            'attachment' => $file['attachment'],
        ];
        $message_attachment = MessageAttachment::create($attachment);
        Flash::success(trans("comman.message_added"));
        $receive_user = User::where('id', $id)->first();
        if($user_type=='client')
        {
            $user           = Auth::user();
            $send_user_name = $user->name;
            $email          = $receive_user->email;
            $name           = $receive_user->name;

            $email_template = EmailTemplate::where('slug', 'client-leave-message-to-coach')->first()->toArray();
                if (isset($email_template)) {
                    $tag         = ['[client-email]','[coach-email]','[coach-first-name]','[client-name]', '[client-first-name]'];
                    $replace_tag = [$user->email,$receive_user->email,$receive_user->name, $send_user_name,$user->first_name];
                    $to          = str_replace($tag, $replace_tag, $email_template['to']);
                    $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                    $content     = str_replace($tag, $replace_tag, $email_template['content']);
                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
        }
        if (($user_type == 'user' || $user_type == 'coach') && $receive_user->user_type == 'client' && config('app.env', false) !== "local")
        {
            $user           = Auth::user();
            $send_user_name = $user->name;
            $email          = $receive_user->email;
            $name           = $receive_user->name;
            $subject        = '';
            if ($user_type == 'coach') {
                $subject        = 'You have a new message from ' . $send_user_name;
                $email_template = EmailTemplate::where('slug', 'coach-leaves-a-message-within-the-system')->first()->toArray();
                if (isset($email_template)) {
                    $tag         = ['[client-email]', '[client-name]', '[coach-name]'];
                    $replace_tag = [$email, $name, $send_user_name];
                    $to          = str_replace($tag, $replace_tag, $email_template['to']);
                    $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                    $content     = str_replace($tag, $replace_tag, $email_template['content']);
                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                } else {
                    Mail::send(
                        'email_template.message', ['sender_name' => $send_user_name, 'email' => $email, 'receiver_name' => $name], function ($message) use ($email, $subject) {
                            $message->to($email)->subject($subject);
                            $bcc = (!empty(config('srtpl.bccmail'))) ? explode(',', config('srtpl.bccmail')) : '';
                            // dump(config('srtpl.bccmail')); exit();
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
            } else {
                $subject        = 'You have a new message from Life Process Program Admin';
                $email_template = EmailTemplate::where('slug', 'admin-leaves-a-message-within-the-system')->first()->toArray();
                if (isset($email_template)) {
                    $tag         = ['[client-email]', '[client-name]', '[admin-name]'];
                    $replace_tag = [$email, $name, $send_user_name];
                    $to          = str_replace($tag, $replace_tag, $email_template['to']);
                    $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                    $content     = str_replace($tag, $replace_tag, $email_template['content']);
                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                } else {
                    Mail::send(
                        'email_template.message', ['sender_name' => $send_user_name, 'email' => $email, 'receiver_name' => $name], function ($message) use ($email, $subject) {
                            $message->to($email)->subject($subject);
                            $bcc = (!empty(config('srtpl.bccmail'))) ? explode(',', config('srtpl.bccmail')) : '';
                            // dump(config('srtpl.bccmail')); exit();
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
            }
        }
        return redirect()->back();
    }

    public function get_messages($user_id)
    {
        $messages = Message::where("messages.deleted", "0")->with('attachment')->where('create_user_id', '=', $user_id)->orwhere('receive_user_id', '=', $user_id)->orderby('id')->get();
        return $messages;
    }

    public function contact_admin()
    {
        $default_message = Client::with('program')->where('user_id',$this->user->id)->first();
        // echo "<pre>"; print_r($default_message->toArray()); exit();
        $timezone  = Auth::user()->timezone;

        $user_id   = Auth::id();

        $all_admin = User::where('user_type', 'user')->pluck('id')->toArray();
        $updated = Message::where('receive_user_id', '=', Auth::id())->where('create_user_id', '=', '1')->update(['is_read' => 1]);
        if (isset($all_admin) && count($all_admin) > 0) {
            $client_messages = Message::whereIn('receive_user_id', $all_admin)->where('create_user_id', $user_id)->get();
            $admin_messages  = Message::whereIn('create_user_id', $all_admin)->where('receive_user_id', $user_id)->get();
            $messages        = $client_messages->merge($admin_messages);
            $messages        = $messages->unique('messages')->sortBy('id');
            view()->share('messages', $messages);
            view()->share('timezone', $timezone);
            view()->share('default_message', $default_message);
            return view('messages.contact_admin');
        }
    }
    public function client_admin()
    {

        $timezone  = Auth::user()->timezone;
        $user_id   = Auth::id();
        $all_admin = User::where('user_type', 'user')->pluck('id')->toArray();

        $coach_id = Client::where('user_id', $user_id)->get(['coach_id']);

        if (isset($all_admin) && count($all_admin) > 0) {
            $client_messages = Message::whereIn('receive_user_id', $all_admin)->where('create_user_id', $user_id)
                ->select('messages.*', DB::raw('image'))
                ->Leftjoin('users', 'users.id', '=', 'messages.receive_user_id')
                ->get();
            //echo $client_messages;exit;
            $admin_messages = Message::whereIn('create_user_id', $all_admin)->where('receive_user_id', $user_id)
                ->select('messages.*', DB::raw('image'))
                ->Leftjoin('users', 'users.id', '=', 'messages.create_user_id')
                ->get();
            //echo $admin_messages;exit;
            $messages = $client_messages->merge($admin_messages);
            //echo $messages;exit;
            //dd($messages);
            $messages = $messages->unique('messages')->sortBy('id');
            $updated = Message::where('receive_user_id', '=', $user_id)->update(['is_read' => 1]);

            view()->share('coach', $coach_id);
            view()->share('messages', $messages);
            view()->share('timezone', $timezone);

            return view('messages.client.client-admin-panel');
        }
    }
    public function contactAdminStore(Request $request)
    {
        //dd($request->all());
        $file['attachment'] = '';
        if ($request->hasFile('attachment')) {
            $file['attachment'] = \AppHelper::getUniqueFilename($request->file('attachment'), AppHelper::path('uploads/messages/attachment/')->getImagePath());
            $request->file('attachment')->move(AppHelper::path('uploads/messages/attachment/')->getImagePath(), $file['attachment']);
        }
        $result = $this->validate($request, [
            'messages' => "required",
        ],
            [
                'messages.required' => 'The Message field is required.',
            ]);
        $all_admin = User::where('user_type', 'user')->get(['id']);
        if (isset($all_admin) && count($all_admin) > 0) {
            foreach ($all_admin as $admin) {
                $input = [
                    'create_user_id'  => Auth::id(),
                    'receive_user_id' => $admin->id,
                    'messages'        => $request->get('messages'),
                ];
                $model = Message::create($input);
            }

            $attachment =
                [
                'message_id' => $model->id,
                'attachment' => $file['attachment'],
            ];
            $message_attachment = MessageAttachment::create($attachment);
        }

        Flash::success(trans("comman.message_added"));
        return redirect()->route('messages.contact-admin');
    }
    public function contactAdminClientStore(Request $request)
    {
         $file['attachment'] = '';
        if ($request->hasFile('attachment')) {
            $file['attachment'] = \AppHelper::getUniqueFilename($request->file('attachment'), AppHelper::path('uploads/messages/attachment/')->getImagePath());
            $request->file('attachment')->move(AppHelper::path('uploads/messages/attachment/')->getImagePath(), $file['attachment']);
        }

        $result = $this->validate($request, [
            'messages' => "required",
        ],
            [
                'messages.required' => 'The Message field is required.',
            ]);
        $all_admin = User::where('user_type', 'user')->get(['id']);
        if (isset($all_admin) && count($all_admin) > 0) {
            foreach ($all_admin as $admin) {
                $input = [
                    'create_user_id'  => Auth::id(),
                    'receive_user_id' => $admin->id,
                    'messages'        => $request->get('messages'),
                ];
                $model = Message::create($input);
                  $attachment =
                [
                'message_id' => $model->id,
                'attachment' => $file['attachment'],
            ];
            $message_attachment = MessageAttachment::create($attachment);
            }
        }

        Flash::success(trans("comman.message_added"));
        return redirect()->back();
    }
    public function getSortUserList($users, $toArray = true)
    {
    	$users = $users->sortByDesc(function($row){
								if($row->receive->count()) {
                                    return $row->receive->first()->id;
								} else if($row->send->count()) {
                                    return $row->send->first()->id;
                                } else {
									return 0;
								}
							})->values();
    	if($toArray)
    	{

            $users=$users->toArray();
    	}
        //dd($users);
    	return $users;
    }
    public function meeting()
    {
        $user_type = Auth::user()->user_type;
        $currenttime =  Carbon::now()->format('Y-m-d H:i:s');
        $id = Auth::id();

        //working out users timezone so we can use it in the view.
        if(!empty(Auth::user()->timezone) && isset(Auth::user()->timezone)){
            $timezone = Auth::user()->timezone;
        }else{
            $timezone = "UTC";
        }
        view()->share('timezone', $timezone);

        if($user_type=='client')
        {
            $request = DB::table('meeting')->where('client_id',$id)->where('end_datetime','>=',$currenttime)->orderby('start_datetime','ASC')->get();
        }
        else
        {

            $request = DB::table('meeting')->where('coach_id',$id)->where('start_datetime','<=',$currenttime)->where('end_datetime','>=',$currenttime)->orderby('start_datetime','ASC')->get();
          //  dd($request);
        }
        // if($user_type=='client')
        // {
        //     $request = DB::table('meeting')->where('client_id',$id)->where('start_datetime','<=',$currenttime)->where('end_datetime','>=',$currenttime)->get();
        // }
        // else
        // {
        //     $request = DB::table('meeting')->where('coach_id',$id)->where('start_datetime','<=',$currenttime)->where('end_datetime','>=',$currenttime)->get();
        // }
        if(empty($request))
        {
            $request="";
        }
        view()->share('request', $request);
        return view('messages.meeting');
    }
    public function createmeeting(Request $request)
    {
         //dd($request);
         $data = array_merge([
            'key' => 'REI5vVhhQh-bQkbwjkGdLA',
            'secret' => 'zILvDmHHTJ2mBhMFIzNAtyYa7F7iZllcA5oC',
         ]);

        $getuser = array_merge([
            'api_key' => 'REI5vVhhQh-bQkbwjkGdLA',
            'api_secret' => 'zILvDmHHTJ2mBhMFIzNAtyYa7F7iZllcA5oC',
            'email' => $request->get('email'),
            'login_type' => '100'
        ]);
        $action='v1/user/getbyemail';
        //$action='user/create';

        $foo = new Zoom($data);
        $request=$foo->sendRequest($action,$getuser);
        //print_r($request); exit;
        $createAMeetingArray = array_merge([
            'api_key' => 'REI5vVhhQh-bQkbwjkGdLA',
            'api_secret' => 'zILvDmHHTJ2mBhMFIzNAtyYa7F7iZllcA5oC',
            'host_id' => $request->id,
            'type'=>'2',
            'topic' => 'welcome'
        ]);
        $host_id=$request->id;
        $action='v1/meeting/create';
        $foo = new Zoom($data);
        $request=$foo->sendRequest($action,$createAMeetingArray);
        //print_r($request); exit;
        //prev(array)int_r($request->join_url); exit;
        $user_type = Auth::user()->user_type;
        // dd($user_type);
        if($user_type=='client')
        {
            $id = Auth::id();
            //$coach = Coach::findOrFail($id);
            //dd($id);
            $client=Client::where('user_id',$id)->first()->toArray();
            $coach_id=$client['coach_id'];
            $coach=Coach::where('id',$coach_id)->first()->toArray();
            if(isset($coach['meeting_id']) && !empty($coach['host_id']))
            {
                $action='v1/meeting/get';
                $getmeeting=array_merge([
                'api_key' => 'REI5vVhhQh-bQkbwjkGdLA',
                'api_secret' => 'zILvDmHHTJ2mBhMFIzNAtyYa7F7iZllcA5oC',
                'id'=> $coach['meeting_id'],
                'host_id' => $coach['host_id'],
                ]);
                //print_r($getmeeting); exit();
                $request=$foo->sendRequest($action,$getmeeting);
            }
            else
            {
                $request="";
            }
            //print_r($request); exit;
        }
        else
        {
            $id = Auth::id();
            Coach::where('user_id',$id)->update(['meeting_id' => $request->id, 'host_id'=>$host_id]);
        }
        view()->share('request', $request);
        return view('messages.meeting');
    }



    public function messages_view(Request $request, User $user){
        $timezone  = Auth::user()->timezone;
        $userDetails = User::where('deleted', '=', '0')->where('id', '=', $user->id)->with('client')->first();

        if($userDetails->client->invite_coach != Auth::user()->email){
            return redirect()->back()->withErrors('You do not have permissions to view this content');
        }

        $messages = Message::where('create_user_id', '=', $user->id)->orWhere('receive_user_id', '=', $user->id)->with('userCreator')->get();

        return view('messages/read-only-coach/view', compact('messages', 'user', 'timezone'));
    }

}
