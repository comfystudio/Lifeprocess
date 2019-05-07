<?php

namespace App\Http\Controllers;

use App\Models\CreditPackage;
use Auth;
use Flash;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CreditPakageController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:credit_package.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:credit_package.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:credit_package.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:credit_package.delete', ['only' => ['destroy']]);
		$this->title = "Credit Packages";
		view()->share('title', $this->title);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addpackage"), "url" => route('creditpackage.create', ['_url' => request()->getRequestUri()]),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('credit_package.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('packages', $this->get_index(array(), array()));
		view()->share('counter', 0);
		return view('creditpackage.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url', route('creditpackage.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		view()->share('title', "Credit Package");
		return view("creditpackage.create");
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

			'price' => "required|numeric",
			'credit' => "required|numeric|unique:credit_packages,credit,NULL,id,deleted,0",
		]
		);
		$model = CreditPackage::create($input);
		Flash::success(trans("comman.package_added"));
		if ($request->get('save_exit')) {
			return redirect(request()->get('_url', route('creditpackage.index')));
		} else {
			return redirect()->route('creditpackage.create', ['_url' => request()->get('_url', route('creditpackage.index'))]);
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
		$id = Crypt::decryptString($id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('creditpackage.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$package = CreditPackage::find($id);
		if (is_null($package)) {
			return redirect(request()->get("_url", route('creditpackage.index')));
		}
		view()->share('title', "Credit Package");
		return view('creditpackage.edit', compact('package'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$id = Crypt::decryptString($id);
		$package = CreditPackage::findOrFail($id);
		$input = $request->all();
		$result = $this->validate($request, [
			'price' => "required|numeric",
			'credit' => "required|numeric|unique:credit_packages,credit,{$id},id,deleted,0",
		]
		);
		$package->update($input);
		Flash::success(trans("comman.package_updated"));
		if ($request->get('save_exit')) {
			return redirect()->route('creditpackage.index');
		} else {
			return redirect()->route('creditpackage.edit', Crypt::encryptString($id));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {

		$model = CreditPackage::find($id);
		if ($model) {
			$model->deleted = '1';
			$model->save();
			Flash::success(trans("comman.package_deleted"));
		} else {
			Flash::error(trans("comman.package_error"));
		}

		return redirect(request()->get('_url', route('creditpackage.index')));
	}
	public function get_index($filters = array(), $sort_order = array()) {

		$models = CreditPackage::where("credit_packages.deleted", "0");
		$models->select(array(
			"credit_packages.*",
		));
		if (request()->get('credit', false)) {
			$models->where('credit', 'like', "%" . request()->get("credit") . "%");
		}
		if (request()->get('price', false)) {
			$models->where('price', 'like', "%" . request()->get("price") . "%");
		}
		if (request()->get('status', false)) {
			$models->where('status', '=', request()->get("status"));
		}

		if (!empty($filters) && is_array($filters)) {
			foreach ($filters as $column => $row) {
				if (!empty($column) && !empty($row["value"]) && is_array($row)) {
					if ($row["operator"] == "like") {
						$models->where("credit_packages." . $column, $row["operator"], "%" . $row["value"] . "%");
					} else {
						$models->where("credit_packages." . $column, $row["operator"], $row["value"]);
					}
				}
			}
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('credit_packages.id', 'DESC');
		}

		$per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
}
