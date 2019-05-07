<?php

namespace App\Http\Controllers;

use App;
use Auth;

class DashboardController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Dashboard";
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		if(Auth::user()->user_type != 'user') {
			return redirect()->route('login');
		}
		// dump(Auth::user()); exit();
		//get Newest Clients.. register with in current month
		$client = App::make('App\Http\Controllers\ClientController');
		$client = $client->get_newest_clients();
		view()->share('newest_clients', $client);

		//get Newest Coaches.. register with in current month
		$coach = App::make('App\Http\Controllers\CoachController');
		$coach = $coach->get_newest_coaches();
		view()->share('newest_coaches', $coach);

		$upcoming_sessions = App::make('App\Http\Controllers\AllSessionController');
		$filters = array(
			'session_status' => [
				'operator' => 'null',
				'value' => 'NULL',
			],
		);
		$upcoming_sessions = $upcoming_sessions->get_index($limit = 5, $filters);
		view()->share('upcoming_sessions', $upcoming_sessions);

		//get Program with client
		$program = App::make('App\Http\Controllers\ProgramController');
		$program = $program->get_index(array());
		view()->share('programs', $program);

		// get total client count in 30 day and 60 day
		$client_count = App::make('App\Http\Controllers\UserController');
		$client_count = $client_count->getCountClientLogin();
		view()->share('client_count',$client_count);

		view()->share('title', $this->title);
		return view('dashboard');
	}

}
