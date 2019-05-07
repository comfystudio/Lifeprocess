<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoachSceduleBooked;
use App\Models\User;
use App\Models\ClientScheduledSessionProblem;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use AppHelper;  
use Carbon\Carbon;
use Mail;
use Flash;

class ClientScheduledSessionProblemController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:scheduled_session_problem.create', ['only' => ['create', 'store']]);
        // $this->middleware('check_for_permission.access:clients.view', ['only' => ['index', 'show']]);
        // $this->middleware('check_for_permission.access:clients.update', ['only' => ['edit', 'update']]);
        // $this->middleware('check_for_permission.access:clients.delete', ['only' => ['destroy']]);
        $this->title = 'Scheduled Session Problem';
        //$this->ajax = new AjaxController();
    }

    /**
     * Get a validator for an incoming creating/updating request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $mode = 'create', $edit_rules = array()) // $mode = create / edit
    {
        $rules = [
            'problem' => 'required',
            'other' => 'required_if:problem,other'
        ];
        return Validator::make($data, $rules);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $scheduled_session_id)
    {
        $scheduled_session_id = Crypt::decryptString($scheduled_session_id);
        view()->share('title', $this->title);
        //43
        $scheduled_session = CoachSceduleBooked::with(['coach_schedule.user', 'client.user'])->where('id', $scheduled_session_id)->whereNull('session_status')->first();
        view()->share('scheduled_session_id', $scheduled_session_id);
        if(!empty($scheduled_session)) {          
            // dump(config('srtpl.problems'))  ; exit();
            view()->share('problems', config('srtpl.problems'));
            view()->share('scheduled_session', $scheduled_session);
            return view('bookschedule.clientScheduledSessionProblem');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $scheduled_session_id)
    {
        $scheduled_session_id = Crypt::decryptString($scheduled_session_id);
        $input = AppHelper::getTrimmedData($request->all());
        $this->validator($request->all())->validate();
        try {
            \DB::beginTransaction();
            $input['client_session_id'] = $scheduled_session_id;
            $input['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $problem_added = ClientScheduledSessionProblem::create($input);
            $scheduled_session = CoachSceduleBooked::with(['coach_schedule.user', 'client.user'])->where('id', $scheduled_session_id)->whereNull('session_status')->first();

            $adminUser = User::where('status', 'active')->where('user_type', 'user')->first();
            Mail::send('email_template.sendProblemAlertToAdmin', ['problem_added' => $problem_added, 'scheduled_session' => $scheduled_session, 'adminUser' => $adminUser], function ($mail) use ($adminUser) {
                $mail->to($adminUser->email)
                    ->bcc('urja.satodiya@sphererays.net')
                    ->subject('Client had problem with scheduled session!');
            });
            \DB::commit();
        } catch(Exception $e) {
            \DB::rollback();
            \Log::error($e->getMessage());
        }
        Flash::success('Your Session problem has been sent to admin. He will look and reapply your credits into your account.');
        return redirect()->route('client.dashboard');
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
