<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Auth;
use Flash;
use Illuminate\Http\Request;

class FaqController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:faqs.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:faqs.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:faqs.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:faqs.delete', ['only' => ['destroy']]);
		$this->title = "FAQ";
		view()->share('title', $this->title);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addfaq"), "url" => route('faqs.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('faqs.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('faqs', $this->get_index(array()));
		view()->share('counter', 0);
		return view('faqs.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('faqs.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		return view("faqs.create");
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$request['answer'] = str_replace('<p><br></p>', '', $request->get('answer'));
		$result = $this->validate($request, [
			'question' => "required",
			'answer' => "required",
		]
		);
		$role_id = Auth::id();
		$question = $request->get('question');
		$answer = $request->get('answer');
		$input = [
			'role_id' => $role_id,
			'question' => $question,
			'answer' => $answer,

		];
		$model = Faq::create($input);
		Flash::success(trans("comman.faq_added"));
		if ($request->get('save_exit')) {
			return redirect()->route('faqs.index');
		} else {
			return redirect()->route('faqs.create');
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
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('faqs.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$faq = Faq::find($id);
		if (is_null($faq)) {
			return redirect()->route('faqs.index');
		}
		return view('faqs.edit', compact('faq'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$request['answer'] = str_replace('<p><br></p>', '', $request->get('answer'));
		$result = $this->validate($request, [
			'question' => "required",
			'answer' => "required",
		]);
		$faq = Faq::findOrFail($id);
		$role_id = Auth::id();
		$question = $request->get('question');
		$answer = $request->get('answer');
		$input = [
			'role_id' => $role_id,
			'question' => $question,
			'answer' => $answer,

		];

		$faq->update($input);
		Flash::success(trans("comman.faq_updated"));
		if ($request->get('save_exit')) {
			return redirect()->route('faqs.index');
		} else {
			return redirect()->route('faqs.edit', $id);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$model = Faq::find($id);
		if ($model) {
			$model->deleted = '1';
			$model->save();
			Flash::success(trans("comman.faq_deleted"));
		} else {
			Flash::error(trans("comman.faq_error"));
		}
		return redirect()->route('faqs.index');
	}
	public function get_index($sort_order) {

		$models = Faq::where("faqs.deleted", "0");
		$models->select(array(
			"faqs.*",
		));
		if (request()->get('question', false)) {
			$models->where('question', 'like', "%" . request()->get("question") . "%");
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('faqs.id', 'DESC');
		}
		return $models->get();

		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
}
