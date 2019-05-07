<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Auth;
use Flash;
use Cache;

class CountryController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:countries.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:countries.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:countries.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:countries.delete', ['only' => ['destroy']]);
		$this->title = "Countries";
		view()->share('title', $this->title);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addcountry"), "url" => route('countries.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('countries.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('countries', $this->get_index(array()));
		view()->share('counter', 0);
		return view('countries.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('countries.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		return view('countries.create');
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
			'country' => "required|unique:countries,country,NULL,id,deleted,0",
			'country_code' => "required|unique:countries,country_code,NULL,id,deleted,0"]
		);

		$model = Country::create($input);
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $model,
			]);
		}
		Flash::success(trans("comman.country_added"));
		if ($request->get('save_exit')) {
			return redirect()->route('countries.index');
		} else {
			return redirect()->route('countries.create');
		}
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
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('countries.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$country = Country::find($id);
		if (is_null($country)) {
			return redirect()->route('countries.index');
		}

		return view('countries.edit', compact('country'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$country = Country::findOrFail($id);

		$input = $request->all();
		$result = $this->validate($request, [
			'country' => "required|unique:countries,country,{$id},id,deleted,0",
			'country_code' => "required|unique:countries,country_code,{$id},id,deleted,0",
			// 'country' => [
			//     'required',
			//     Rule::unique('countries')->ignore($id),
			// ]
		]);

		$country->update($input);

		Flash::success(trans("comman.country_updated"));

		if ($request->get('save_exit')) {
			return redirect()->route('countries.index');
		} else {
			return redirect()->route('countries.edit', $id);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$model = Country::find($id);
		// dd($model);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				Flash::success(trans("comman.country_deleted"));
			} else {
				Flash::error(trans("comman.country_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.country_error"));
		}

		return redirect()->route('countries.index');
	}

	// function to get the listing for the index page...
	public function get_index($sort_order) {
		$models = Country::where("countries.deleted", "0");
		$models->select(array(
			"countries.*",
		));
		if (request()->get('country', false)) {
			$models->where('country', 'like', "%" . request()->get("country") . "%");
		}
        $sort_order = request()->get('sort_order',[]);
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('countries.id', 'DESC');
		}
		$per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}

	public function ajaxCountries(Request $request, $param = array()) {
		return Country::pluck('country', 'id')->toArray();
	}
}
