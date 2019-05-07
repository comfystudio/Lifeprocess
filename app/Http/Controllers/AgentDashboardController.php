<?php

namespace App\Http\Controllers;

use App;
use Auth;

class AgentDashboardController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Agent Dashboard";
		$this->module_title = "Welcome to Life Process Program";
	}

	/**
	 * Show the client application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		if(Auth::user()->user_type != 'agent') {
			return redirect()->route('login');
		}
		view()->share('title', $this->title);
		view()->share('module_title', $this->module_title);
		$obj = App::make('App\Http\Controllers\AgentController');
		$dashboardStatistics = $obj->getAgentDashboardStatistics();
		view()->share('dashboardStatistics', $dashboardStatistics);
		return view('agents.dashboard.agent-dashboard');
	}

}
