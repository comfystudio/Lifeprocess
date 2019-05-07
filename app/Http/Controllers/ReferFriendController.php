<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ReferFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class ReferFriendController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:refer_friend.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:refer_friend.view', ['only' => ['index', 'show']]);
		$this->title = "Refer Your Friends";
		view()->share('title', $this->title);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$page = Page::where('slug', 'help-friend')->first();
		view()->share('page', $page);
		view()->share('message_defualt_text', config('srtpl.message_defualt_text'));
		view()->share('referals', $this->get_index(array()));
		view()->share('counter', 0);
		return view('referfriend.create');

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {

		$user_id = Auth::id();
		$request->request->add(['create_user_id' => $user_id]);
		$input = $request->all();
		//dd($input);
		$result = $this->validate($request,
			[
				'name' => "required",
				'email' => "required|email",
				'friends_email' => "required|email|different:email",
				'message' => "required",
			]
		);
		$model = ReferFriend::create($input);
		//Mail send to friend
		$email = $input['email'];
		$friend_email = $input['friends_email'];
		Mail::send(
			'email_template.referfriend', ['email' => $email, 'name' => $input['name'], 'use_name' => $input['use_your_name'],'messages'=>$input['message']], function ($message) use ($friend_email) {
				$message->to($friend_email)->subject('A website From Life Process');
				$bcc = explode(',', config('srtpl.bccmail'));
				if (!empty($bcc)) {
					$message->bcc($bcc);
				}
			});
		$page = Page::where('slug', 'thank-you-page-content')->first();
		view()->share('page', $page);
		return view('referfriend.thankyoupage');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
	public function get_index($sort_order) {
		$user_id = Auth::id();
		$models = ReferFriend::where('create_user_id', '=', $user_id);
		$models->select(array(
			"refer_friends.*",
		));
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('refer_friends.id', 'DESC');
		}
		return $models->get();
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
}
