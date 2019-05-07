<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Auth;
use Flash;
use Cache;


class PageController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:pages.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:pages.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:pages.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:pages.delete', ['only' => ['destroy']]);
		$this->title = "Site Pages";
		view()->share('title', $this->title);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addpage"), "url" => route('pages.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('pages.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('pages', $this->get_index(array()));
		view()->share('counter', 0);
		return view('pages.index');

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('pages.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		return view("pages.create");
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
			'title' => "required",
			'slug' => "required",
			'content' => "required",

		]
		);
		$model = Page::create($input);
		Flash::success(trans("comman.page_added"));
		if ($request->get('save_exit')) {
			return redirect()->route('pages.index');
		} else {
			return redirect()->route('pages.create');
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
		if (request()->get('_url')) {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		} else {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('pages.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$page = Page::find($id);
		if (is_null($page)) {
			return redirect()->route('pages.index');
		}
		return view('pages.edit', compact('page'));
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$page = Page::findOrFail($id);
		$input = $request->all();
		$result = $this->validate($request, [
			'title' => "required",
			'slug' => "required",
			'content' => "required",
		]);
		$page->update($input);
		Flash::success(trans("comman.page_updated"));

		if ($request->get('save_exit')) {
			if($request->get('_url'))
			{
				return redirect($request->get('_url'));
			}
			else
			{
				return redirect()->route('pages.index');	
			}
		} else {
			return redirect()->route('pages.edit', $id);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$model = Page::find($id);
		if ($model) {
			$model->deleted = '1';
			$model->save();
			Flash::success(trans("comman.page_deleted"));

		} else {
			Flash::error(trans("comman.page_error"));
		}
		return redirect()->route('pages.index');
	}

	public function get_index($sort_order) {

		$models = Page::where("pages.deleted", "0");
		$models->select(array(
			"pages.*",
		));
		if (request()->get('title', false)) {
			$models->where('title', 'like', "%" . request()->get("title") . "%");
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('pages.id', 'DESC');
		}
		$per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
		//return $models->get();
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}

    public function group(){
        $group = Page::where('slug', '=', 'group-meetings')->first();
        $title = 'Group Meeting';

        return view('pages/group', compact('group', 'title'));
    }
}
