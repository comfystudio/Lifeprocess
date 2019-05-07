<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;
use Flash;
use Illuminate\Http\Request;
use Mail;

class ContactusController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:contact.create', ['only' => ['create', 'store']]);
		$this->title = "Contact us";
		view()->share('title', $this->title);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$page = Page::where('slug', 'contact-us')->first();
		view()->share('page', $page);
		return view('contact.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$input = $request->all();
		$result = $this->validate($request, [

			'name' => "required",
			'email' => "required|email",
			'subject' => "required",
			'message' => "required",

		]);
		$send_email = Setting::where('name', 'contact_us_email')->first()->value;
		Mail::send(
			'email_template.contactus', ['name' => $input['name'], 'email' => $input['email'], 'subject' => $input['name'], 'client_message' => $input['message']], function ($message) use ($send_email) {
				$message->to($send_email)->subject('Contact form enquirey');
				$bcc = (!empty(config('srtpl.bccmail'))) ? explode(',', config('srtpl.bccmail')) : '';
				if (!empty($bcc)) {
					$message->bcc($bcc);
				}
			});
		Flash::success(trans("comman.enquirey_added"));
		return redirect(request()->get('_url', route('contact')));

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

}
