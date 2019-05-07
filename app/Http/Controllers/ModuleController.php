<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Module;
use App\Models\Program;
use App\Models\CoachModuleRate;
use Cache;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ModuleController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = trans("comman.module");
		view()->share('title', $this->title);
		// AppHelper::path('uploads/module/reading_materials/');
		// AppHelper::path('uploads/program/icons/');

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
			'program_id' => 'required',
			'module_no' => 'required|numeric',
			'status' => 'required',
			'introduction_video' => 'max:255',
			'delay_btw_chapter_exercise' => 'integer',
		];

		if ($mode == 'edit') {
			foreach ($edit_rules as $field => $rule) {
				$rules[$field] = $rule;
			}
		} else {
			// $rules['module_title'] = "required|unique:modules,module_title,NULL,id,deleted,0";
		}

		return Validator::make($data, $rules);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($program_id) {

		$program_id = Crypt::decryptString($program_id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.programs"), "url" => route('programs.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => trans('comman.programs'))),
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addmodule"), "url" => route('modules.create', ['program_id' => Crypt::encryptString($program_id), '_url' => request()->getRequestUri()]),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		));

		view()->share('module_status', $this->module_status());
		view()->share('modules', $this->get_index($program_id, array()));
		view()->share('counter', 0);
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		return view('modules.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, $program_id) {
		$program_id = Crypt::decryptString($program_id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		view()->share('module_status', $this->module_status());
		view()->share('title', trans("comman.module"));
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		if (isset(Cache::get('settings')['default_delay_between_modules'])) {
			$default_delay = Cache::get('settings')['default_delay_between_modules'];
			view()->share('default_delay', $default_delay);
		}
		return view('modules.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($program_id, Request $request) {

		$input = $request->all();

		$rules = array();
		$rules['module_title'] = [
			'required',
			'max:255',
			Rule::unique('modules')->where(function ($query) use ($program_id) {
				$query->where('program_id', $program_id)->where('deleted', '0');
			}),
		];
		$this->validator($request->all(), 'edit', $rules)->validate();
		// dump($input); exit();
		$file['reading_material'] = '';
		if ($request->hasFile('reading_material')) {
			$file['reading_material'] = \AppHelper::getUniqueFilename($request->file('reading_material'), AppHelper::path('uploads/module/reading_materials/')->getImagePath());
			$request->file('reading_material')->move(AppHelper::path('uploads/module/reading_materials/')->getImagePath(), $file['reading_material']);
			$input['reading_material'] = $file['reading_material'];
		}

		if (empty($input['delay_btw_chapter_exercise'])) {
			$input['delay_btw_chapter_exercise'] = 0;
			if (isset(Cache::get('settings')['default_delay_between_modules'])) {
				$input['delay_btw_chapter_exercise'] = Cache::get('settings')['default_delay_between_modules'];
			}
		}
		$input=['program_id'=>$input['program_id'],
				'module_title'=>$input['module_title'],
				'module_no'=>$input['module_no'],
				'status'=>$input['status'],
				'introduction_video'=>$input['introduction_video_fixed'],
				'delay_btw_chapter_exercise'=>$input['delay_btw_chapter_exercise'],
				];
		//dd($input);
		$model = Module::create($input);
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $model,
			]);
		}
		Flash::success(trans("comman.module_added"));

		if ($request->get('save_exit')) {
			return redirect(request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
		} else {
			return redirect()->route('modules.create', ['program_id' => Crypt::encryptString($program_id), '_url' => request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)]))]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return Response
	 */
	public function show($program_id, $id) {
		$program_id = Crypt::decryptString($program_id);
		$id = Crypt::decryptString($id);
		$state = Module::findOrFail($id);

		view()->share('reading_material',$state->reading_material);
		view()->share('program_id', $program_id);
		return view('modules.show', compact('state'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return Response
	 */
	public function edit($program_id, $id, Request $request) {
		$program_id = Crypt::decryptString($program_id);
		$id = Crypt::decryptString($id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('modules.index', ['program_id' => Crypt::encryptString($program_id)])),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		$module = Module::find($id);
		view()->share('reading_material',$module->reading_material);
		if (is_null($module)) {
			return redirect(request()->get("_url", route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
		}
		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));
		view()->share('module_status', $this->module_status());
		view()->share('title', trans("comman.module"));
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		return view('modules.edit', compact('module'));
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 *
	 * @return Response
	 */
	public function update($program_id, $id, Request $request) {

		$program_id = Crypt::decryptString($program_id);
		$id = Crypt::decryptString($id);
		$module = Module::findOrFail($id);
		//dd($state);
		$input = $request->all();

		$extra_rules = array(
			// 'module_title' => "required|unique:modules,module_title,{$id},id,deleted,0"
			'module_title' => [
				'required',
				'max:255',
				Rule::unique('modules')->where(function ($query) use ($program_id) {
					$query->where('program_id', $program_id)->where('deleted', '0');
				})->ignore($id),
			],
		);
		$this->validator($request->all(), 'edit', $extra_rules)->validate();

		$file['reading_material'] = '';
		if ($request->hasFile('reading_material')) {
			$file['reading_material'] = \AppHelper::getUniqueFilename($request->file('reading_material'), AppHelper::path('uploads/module/reading_materials/')->getImagePath());
			$request->file('reading_material')->move(AppHelper::path('uploads/module/reading_materials/')->getImagePath(), $file['reading_material']);
			$input['reading_material'] = $file['reading_material'];
		} else {
			unset($input['reading_material']);
		}
		$module->update($input);

		if (empty($input['delay_btw_chapter_exercise'])) {
            $default_delay = Cache::get('settings')['default_delay_between_modules'];
			$input['delay_btw_chapter_exercise'] =  $default_delay;
		}
				$input=['program_id'=>$input['program_id'],
				'module_title'=>$input['module_title'],
				'module_no'=>$input['module_no'],
				'status'=>$input['status'],
				'introduction_video'=>$input['introduction_video_fixed'],
				'delay_btw_chapter_exercise'=>$input['delay_btw_chapter_exercise'],

				];
		//introduction_video_fixed
		//dd($input);
		$module->update($input);

		Flash::success(trans("comman.module_updated"));

		if ($request->get('save_exit')) {
			return redirect(request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
		} else {
			// return redirect()->route('modules.edit', ['program_id' => $program_id , 'id' => $id]);
			return redirect(request()->get('_edit_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return Response
	 */
	public function destroy($program_id, $id) {
		$program_id = Crypt::decryptString($program_id);
		$id = Crypt::decryptString($id);
		$model = Module::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				Flash::success(trans("comman.module_deleted"));
			} else {
				Flash::error(trans("comman.module_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.module_error"));
		}
		// return redirect()->route('modules.index', ['program_id' => $program_id]);
		return back();
	}

	// function to get the listing for the index page...
	public function get_index($program_id, $filters = array() ,$sort_order = array()) {
		$models = Module::with('program')->with(['module_exercises' => function ($query) {
			$query->orderBy('exercise_no');
		}])->where("modules.deleted", "0")->where('modules.program_id', $program_id);

		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('modules.module_no');
		}

		return $models->get();

		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}

	public function module_status() {
		return [
			'' => trans('comman.select_status'),
			// 'pending' => trans('comman.pending'),
			// 'in_progress' => trans('comman.in_progress'),
			// 'completed' => trans('comman.completed'),
			// 'submited' => trans('comman.submited'),
			'draft' => trans('comman.draft'),
			'published' => trans('comman.published'),
		];
	}

	public function ajaxModules(Request $request) {
		// return Program::pluck('module_title', 'id')->toArray();
		$data = Module::with(['program' => function ($q) {
			$q->orderBy('id');
		}])->select(array(
			"modules.*",
		))->orderBy('modules.module_no')->get();
		$modules = array();
		foreach ($data as $row) {
			$modules[$row->id] = 'Program ' . $row->program->id . ' - Module ' . $row->module_no . ' - ' . $row->module_title;
		}
		return $modules;
	}
	public function ajaxModulesWithProgramName(Request $request) {
		// return Program::pluck('module_title', 'id')->toArray();
		$data = Module::with(['program' => function ($q) {
			$q->orderBy('id');
		}])->select(array(
			"modules.*",
		))->orderBy('modules.module_no')->get();
		$modules = array();
		foreach ($data as $row) {
			$modules[$row->id] = $row->program->program_name . ' - Module ' . $row->module_no . ' - ' . $row->module_title;
		}
		return $modules;
	}
	/*
		     * Use Ajax Get All modules
	*/

	public function ajaxAllModules(Request $request, $param = array()) {
		if ($request->get('program_id', false)) {
			return Module::where('program_id', $request->get('program_id'))->pluck('module_title', 'id')->toArray();
		} elseif ($request->old('program_id', false) !== false) {
			return Module::where('program_id', $request->old('program_id'))->pluck('module_title', 'id')->toArray();
		} elseif (!empty($param['program_id'])) {
			return Module::where('program_id', $param['program_id'])->pluck('module_title', 'id')->toArray();
		}
		return array();
	}
    public function ajaxcoachmodule(Request $request, $param = array()){
        return $modules = CoachModuleRate::with('module')->where('coach_id',$request->get('coach_id',0))
		->get()->pluck('module.module_title','module.id')->toArray();
    }
	// public function check_module() {
	//     $result = Module::where('state','country_id')->where('deleted','0')->get()->toArray();
	//     if(empty($result)) {
	//         return false;
	//     }
	//     return true;
	// }

	public function AllAjaxModules() {
		$module = Module::pluck('module_title', 'id')->toArray();
		if (!empty($module)) {
			return $module;
		} else {
			return array();
		}
	}
	/*
		    ** Used In Form Fillup
	*/
	public function ajaxLangModules(Request $request, $param = array()) {
		if ($request->get('program')) {
			return Module::where('program_id', $request->get('program'))->pluck('module_title', 'id')->toArray();
		}
		return array();
	}
}
