<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\CoachModuleRate;
use App\Models\CoachProgram;
use App\Models\Program;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class CoachModuleRateController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		// $this->middleware('check_for_permission.access:coaches.create', ['only' => ['create', 'store']]);
		// $this->middleware('check_for_permission.access:coaches.view', ['only' => ['index', 'show']]);
		// $this->middleware('check_for_permission.access:coaches.update', ['only' => ['edit', 'update']]);
		// $this->middleware('check_for_permission.access:coaches.delete', ['only' => ['destroy']]);
		$this->title = "Coach Rate";
		view()->share('title', $this->title);
		view()->share('timezones', get_timezone_list());
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
			'module.*.*' => 'required|numeric|min:0',
		];
		$messages = [
			'module.*.*.required' => 'This rate field is required.',
			'module.*.*.numeric' => 'The rate field must be number.',
			'module.*.*.min' => 'The rate should not be negative.',
		];
		if ($mode == 'edit') {
			foreach ($edit_rules as $field => $rule) {
				$rules[$field] = $rule;
			}
		} else {
		}
		return Validator::make($data, $rules, $messages);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $coach_id) {
		$coach_id = Crypt::decryptString($coach_id);
		// view()->share('module_action', array(
		// 	"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('coaches.index')),
		// 		"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		// ));
		$coach_module_rates = CoachModuleRate::where('coach_id', $coach_id)->get();

		if (is_null($coach_module_rates)) {
			return redirect(request()->get("_url", route('coaches.index')));
		}
		$module_rates = [];
		$module_rates_id = [];
		foreach ($coach_module_rates as $key => $row) {
			$module_rates['module'][$row->program_id][$row->module_id] = $row->rate;
			$module_rates['module_id'][$row->program_id][$row->module_id] = $row->id;
		}

		view()->share('coach_programs', $this->get_programModules($coach_id));
		view()->share('coach_id', $coach_id);
		return view('coaches.coach-rates-create', compact('module_rates'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $coach_id) {
		$coach_id = Crypt::decryptString($coach_id);
		$input = AppHelper::getTrimmedData($request->all());
		$this->validator($request->all())->validate();
		// dump($input); exit();
		if (!empty($input['module']) && isset($input['module'])) {
			CoachModuleRate::where('coach_id', $coach_id)->update(['deleted' => '1']);
			foreach ($input['module'] as $program_id => $modules) {
				foreach ($modules as $module_id => $rate) {
					$sub_array = array();
					$sub_array['coach_id'] = $coach_id;
					$sub_array['program_id'] = $program_id;
					$sub_array['module_id'] = $module_id;
					$sub_array['rate'] = $rate;
					$sub_array['deleted'] = '0';
					if (isset($input['module_id'][$program_id][$module_id]) && !empty($input['module_id'][$program_id][$module_id])) {
						CoachModuleRate::withoutGlobalScope('coach_module_rates.deleted')->where('id', $input['module_id'][$program_id][$module_id])->update($sub_array);
					} else {
						CoachModuleRate::create($sub_array);
					}
				}
			}
		}

		Flash::success(trans("comman.coach_rate_added"));

		// if ($request->get('save_exit')) {
			// return redirect(request()->get('_edit_url', route('programs.index')));
			return redirect()->route('coaches.index');
		// } else {
		// 	return redirect()->route('coach-rates.edit', ['coach_id' => Crypt::encryptString($coach_id), '_url' => request()->get('_url', route('coaches.index'))]);
		// }
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($coach_id) {
		$coach_id = Crypt::decryptString($coach_id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('coaches.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$coach_module_rates = CoachModuleRate::where('coach_id', $coach_id)->get();

		if (is_null($coach_module_rates)) {
			return redirect(request()->get("_url", route('coaches.index')));
		}
		$module_rates = [];
		$module_rates_id = [];
		foreach ($coach_module_rates as $key => $row) {
			$module_rates['module'][$row->program_id][$row->module_id] = $row->rate;
			$module_rates['module_id'][$row->program_id][$row->module_id] = $row->id;
		}

		view()->share('programs', $this->get_programModules());
		view()->share('coach_id', $coach_id);
		return view('coaches.coach-rates-edit', compact('module_rates'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $coach_id) {
		//print_r($_REQUEST);exit;
		$coach_id = Crypt::decryptString($coach_id);

		$input = AppHelper::getTrimmedData($request->all());
		$this->validator($request->all())->validate();
		// dump($input); exit();
		if (!empty($input['module']) && isset($input['module'])) {
			CoachModuleRate::where('coach_id', $coach_id)->update(['deleted' => '1']);
			foreach ($input['module'] as $program_id => $modules) {
				foreach ($modules as $module_id => $rate) {
					$sub_array = array();
					$sub_array['coach_id'] = $coach_id;
					$sub_array['program_id'] = $program_id;
					$sub_array['module_id'] = $module_id;
					$sub_array['rate'] = $rate;
					$sub_array['deleted'] = '0';
					if (isset($input['module_id'][$program_id][$module_id]) && !empty($input['module_id'][$program_id][$module_id])) {
						CoachModuleRate::withoutGlobalScope('coach_module_rates.deleted')->where('id', $input['module_id'][$program_id][$module_id])->update($sub_array);
					} else {
						CoachModuleRate::create($sub_array);
					}
				}
			}
		}

		Flash::success(trans("comman.coach_rate_updated"));

		if ($request->get('save_exit')) {
			// return redirect(request()->get('_edit_url', route('programs.index')));
			return redirect(request()->get('_url', route('coaches.index')));
		} else {
			return redirect()->route('coach-rates.edit', ['coach_id' => Crypt::encryptString($coach_id), '_url' => request()->get('_url', route('coaches.index'))]);
		}
	}

	public function get_programModules($id) {
		$programsModules = CoachProgram::with(['coach_program_detail','coach_program_detail.modules'=> function ($query) {
			$query->orderBy('module_no');
		}])->where('coach_id',$id)->get();
		//dd($programsModules);
		return $programsModules;
	}
}
