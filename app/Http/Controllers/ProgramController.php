<?php

namespace App\Http\Controllers;

use App\Models\Module;
use AppHelper;
use App\Models\Program;
use Auth;
use Cache;
use App\Models\User;
use Flash,DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\UserProgram;
use App\Models\UserModulesExercisesQuestion;
use App\Models\ModulesExercisesQuestion;

class ProgramController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth', ['except' => ['ajaxCoachPrograms']]);
		$this->middleware('check_for_permission.access:programs.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:programs.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:programs.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:programs.delete', ['only' => ['destroy']]);
		AppHelper::path('uploads/program/icons');
		$this->title = trans("comman.programs");
		view()->share('title', $this->title);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addprogram"), "url" => route('programs.create', ['_url' => request()->getRequestUri()]), "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('programs.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('program_status', $this->program_status());
		view()->share('programs', $this->get_index(array()));
		view()->share('counter', 0);
		return view('programs.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url', route('programs.index')), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		view()->share('program_status', $this->program_status());
		return view('programs.create');
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
			'program_name' => "required|unique:programs,program_name,NULL,id,deleted,0|max:255",
			'status' => 'required',
			'sort_description' => 'required',
			'program_fee' => 'required|numeric|min:0',
			'program_icon' => 'image',
			'introduction_video'=>'required',
			'gratuate_video'=>'required',
			'stripe_program_name'=>'required'
		], [
			'program_fee.min' => 'The program fee must be positive.',
		]
		);

		$file['program_icon'] = '';
		$input['program_icon'] = '';
		if ($request->hasFile('program_icon')) {
			$file['program_icon'] = \AppHelper::getUniqueFilename($request->file('program_icon'), AppHelper::getImagePath());
			$request->file('program_icon')->move(AppHelper::getImagePath(), $file['program_icon']);
			$input['program_icon'] = $file['program_icon'];
		}

		$model = Program::create($input);
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $model,
			]);
		}
		Flash::success(trans("comman.program_added"));

		if ($request->get('save_exit')) {
			// return redirect()->route('programs.index');
			return redirect(request()->get('_url', route('programs.index')));
		} else {
			// return redirect()->route('programs.create');
			return redirect()->route('programs.create', ['_url' => request()->get('_url', route('programs.index'))]);
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
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('programs.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$program = Program::find($id);
		if (is_null($program)) {
			return redirect(request()->get("_url", route('programs.index')));
		}

		view()->share('program_status', $this->program_status());
		return view('programs.edit', compact('program'));
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
		$program = Program::findOrFail($id);

		$input = $request->all();

		$result = $this->validate($request, [
			'program_name' => "required|unique:programs,program_name,{$id},id,deleted,0|max:255",
			'status' => 'required',
			'sort_description' => 'required',
			'program_fee' => 'required|numeric|min:0',
			'program_icon' => 'image',
			'introduction_video'=>'required',
			'gratuate_video'=>'required',
			'stripe_program_name'=>'required'
		], [
			'program_fee.min' => 'The program fee must be positive.',
		]);
		// dump($input); exit();
		$file['program_icon'] = '';
		if ($request->hasFile('program_icon')) {
			$file['program_icon'] = \AppHelper::getUniqueFilename($request->file('program_icon'), AppHelper::getImagePath());
			$request->file('program_icon')->move(AppHelper::getImagePath(), $file['program_icon']);
			$input['program_icon'] = $file['program_icon'];
		} else {
			$input['program_icon'] = ($program->program_icon) ?: '';
		}
		//dd($input);
		$program->update($input);

		Flash::success(trans("comman.program_updated"));

		if ($request->get('save_exit')) {
			// return redirect(request()->get('_edit_url', route('programs.index')));
			return redirect(request()->get('_url', route('programs.index')));
		} else {
			return redirect()->route('programs.edit', [Crypt::encryptString($id), '_url' => request()->get('_url', route('programs.index'))]);
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
		$model = Program::find($id);
		// dd($model);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				Flash::success(trans("comman.program_deleted"));
			} else {
				Flash::error(trans("comman.program_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.program_error"));
		}
		return back();
		// return redirect()->route('programs.index');
	}

	// function to get the listing for the index page...
	public function get_index($filters = array(), $sort_order = array()) {
		$models = Program::select(array(
			"programs.*",
			DB::raw("ifnull(clients.active_clients,0) as active_clients")
		))->leftjoin(DB::raw("(SELECT count(clients.id) as active_clients,clients.program_id
        FROM clients
        JOIN users ON users.id = clients.user_id
		AND users.status = 'active'
		AND users.deleted = '0'
            where clients.deleted='0'
            GROUP BY clients.program_id
		) clients "), function ($join) {
            $join->on("clients.program_id", "=",  "programs.id");
        });

		if (request()->get('program_name', false)) {
			$models->where('program_name', 'like', "%" . request()->get("program_name") . "%");
		}
		if (request()->get('status', false)) {
			$models->where('status', request()->get("status"));
		}
		if (!empty($filters) && is_array($filters)) {
			foreach ($filters as $column => $row) {
				if (!empty($column) && !empty($row["value"]) && is_array($row)) {
					if ($row["operator"] == "like") {
						$models->where("programs." . $column, $row["operator"], "%" . $row["value"] . "%");
					} else {
						$models->where("programs." . $column, $row["operator"], $row["value"]);
					}
				}
			}
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('programs.id', 'DESC');
		}
		$per_page = config('srtpl.row_per_page');
		if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        $models->groupBy("programs.id");
        //echo "<pre>"; print_r($models->toSql()); exit();
        return $models->paginate($per_page);
		//return $models->get();
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
	public function program_status() {
		return [
			'' => trans('comman.select_status'),
			'draft' => trans('comman.draft'),
			'published' => trans('comman.published'),
		];
	}
	public function ajaxPrograms(Request $request, $param = array()) {
		return Program::pluck('program_name', 'id')->toArray();
	}
	public function ajaxCoachPrograms(Request $request, $param = array()) {
		$programs = Program::with(['coaches' => function ($query) use ($request, $param) {
			// if ($request->get('coach_id', false)) {
			//     $query->where('coaches.id', $request->get('coach_id'));
			// } else if($request->old('coach_id', false)) {
			//     $query->where('coaches.id', $request->old('coach_id'));
			// } else if(!empty($param['coach_id'])) {
			//     $query->where('coaches.id', $param['coach_id']);
			// }
		}]);
		if (!empty($param['status'])) {
			$programs->where('programs.status', $param['status']);
		}
		$programs = $programs->has('coaches', '>', 0)->get()->toArray();
		$program_lists = array();
		foreach ($programs as $key => $value) {
			if (count($value['coaches']) > 0) {
				//$program_lists[$value['id']] = $value['program_name'].'-($'. $value['program_fee'].')';
				$program_lists[$value['id']] = $value['program_name'];
			}
		}
		return $program_lists;
	}

    public function program_view(User $user){
        $userDetails = User::where('deleted', '=', '0')->where('id', '=', $user->id)->with('client')->first();

        if($userDetails->client->invite_coach != Auth::user()->email){
            return redirect()->back()->withErrors('You do not have permissions to view this content');
        }

        //have to construct our data through the rather convoluted database
        $data = array();
        $programs = UserProgram::where('deleted', '=', '0')->where('user_id', '=', $user->id)->with('program')->get();
        foreach($programs as $program){
            $data[$program->program->program_name] = array();
            $modules = UserModulesExercisesQuestion::where('deleted', '=', '0')->where('user_id', '=', $user->id)->groupBy('module_id')->get();
            foreach($modules as $module){
                $mod = Module::where('deleted', '=', '0')->where('id', '=', $module->module_id)->with('module_exercises')->first();

                $data[$program->program->program_name][$mod->id]['module_title'] = $mod->module_title;
                $data[$program->program->program_name][$mod->id]['exercise_id'] =  $mod->module_exercises[0]->id;
                $questions = UserModulesExercisesQuestion::where('deleted', '=', '0')->where('user_id', '=', $user->id)->where('module_id', '=', $module->module_id)->groupBy('question_id')->pluck('question_id');
                foreach($questions as $question){
                    $quest = ModulesExercisesQuestion::where('deleted', '=', '0')->where('id', '=', $question)->first();
                    $answers = UserModulesExercisesQuestion::where('deleted', '=', '0')->where('module_id', '=', $mod->id)->where('question_id', '=',$question)->where('user_id', "=", $user->id)->first();

                    $data[$program->program->program_name][$mod->id][$quest->id]['question_title'] = $quest->question_title;
                    $data[$program->program->program_name][$mod->id][$quest->id]['answer'] = $answers->answer;
                    $data[$program->program->program_name][$mod->id][$quest->id]['coach_respond'] = $answers->coach_respond;
                }

            }
        }
//        dd($data);
        return view('programs/view', compact('data', 'user'));


    }
}
