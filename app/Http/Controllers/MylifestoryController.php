<?php

namespace App\Http\Controllers;

use App\Models\Mylifestory;
use App\Models\Page;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class MylifestoryController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:my_lifestory.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:my_lifestory.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:my_lifestory.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:my_lifestory.delete', ['only' => ['destroy']]);
		$this->title = "My Life Story";
		view()->share('title', $this->title);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$timezone = Auth::user()->timezone;
		$page = Page::where('slug', 'about-lifestory')->first();
		view()->share('page', $page);
		view()->share('timezone', $timezone);
		view()->share('mylifestory', $this->get_index(array()));
		view()->share('counter', 0);
		return view('mylifestory.create');

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$timezone = Auth::user()->timezone;
		view()->share('timezone', $timezone);
		$page = Page::where('slug', 'about-lifestory')->first();
		view()->share('page', $page);
		view()->share('mylifestory', $this->get_index(array()));
		view()->share('counter', 0);
		return view('mylifestory.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {

		$message = $request->get('message');
		$uid = Auth::id();
		$input = [
			'created_user_id' => $uid,
			'message' => $message,

		];
		$result = $this->validate($request, [
			'message' => "required"]
		);
		$model = Mylifestory::create($input);

		Flash::success(trans("comman.mylifestory_added"));

		if ($request->get('save_exit')) {
			return redirect()->route('mylifestory.index');
		} else {
			return redirect()->route('mylifestory.create');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$id = Crypt::decryptString($id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('clients.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		$timezone = Auth::user()->timezone;
		view()->share('timezone', $timezone);
		$mylifestory = Mylifestory::with('user')->where("mylifestory.deleted", "0")->where('created_user_id', '=', $id)->get();
		$user=User::where('id',$id)->first();
		view()->share('user', $user);
		view()->share('counter', 0);
		return view('mylifestory.show', compact('mylifestory'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {

		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('mylifestory.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$mylifestory = Mylifestory::find($id);
		if (is_null($mylifestory)) {
			return redirect()->route('mylifestory.create');
		}
		return view('mylifestory.edit', compact('mylifestory'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$mylifestory = Mylifestory::findOrFail($id);
		$message = $request->get('message');
		$uid = Auth::id();
		$input = [
			'created_user_id' => $uid,
			'message' => $message,

		];
		$result = $this->validate($request, [
			'message' => "required",

		]);
		$mylifestory->update($input);
		Flash::success(trans("comman.mylifestory_updated"));
		if ($request->get('save_exit')) {
			return redirect()->route('mylifestory.index');
		} else {
			return redirect()->route('mylifestory.index');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$id = Crypt::decryptString($id);
		$model = Mylifestory::find($id);

		if ($model) {
		//Mylifestory::where('id', $model->id)->update(['deleted' => '1']);
		        $model->deleted = '1';
				$model->save();
			Flash::success(trans("comman.mylifestory_deleted"));

		} else {

			Flash::error(trans("comman.mylifestory_error"));
		}
		return redirect()->route('mylifestory.create');
	}

	// function to get the listing for the index page...
	public function get_index($sort_order, $u_id = '') {
		if($u_id){
			$user_id = $u_id;
		}else{
			$user_id = Auth::id();
		}

		$models = Mylifestory::where("mylifestory.deleted", "0")->where('created_user_id', '=', $user_id);
		$models->select(array(
			"mylifestory.*",
		));
		if (request()->get('message', false)) {
			$models->where('message', 'like', "%" . request()->get("message") . "%");
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('mylifestory.id', 'DESC');
		}
		return $models->get();
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
	public function getPDF(Request $request) {

		if($request->has('cl'))
		{
			//$cl=$request->get('cl');
			$cl = Crypt::decryptString($request->get('cl'));
		}
		else
		{
			$cl='';
		}
		$mylifestory = $this->get_index(array(), $cl);
		$counter = 0;
		$timezone = Auth::user()->timezone;
		$pdf = PDF::loadView('mylifestory.pdf_report', ['mylifestory' => $mylifestory, 'counter' => $counter, 'theme' => 'limitless.pdf', 'timezone' => $timezone]);
		$pdf->setPaper('a4');
		$pdf->setOrientation('portrait');
		$pdf->setOption('margin-top', 20);
		$pdf->setOption('margin-right', 15);
		$pdf->setOption('margin-bottom', 15);
		$pdf->setOption('margin-left', 15);
		$pdf->setOption('header-right', '');
		return $pdf->stream('mylifestory.pdf');
		//return $pdf->download("mylifestory.pdf");
	}

}
