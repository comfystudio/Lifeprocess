<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Models\Module;
use App\Models\ModuleExercise;
use AppHelper;
use Flash;
use Auth;
use DB;
use App;

class ModuleExerciseController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->title = trans('comman.module_exercise');
        view()->share('title', $this->title);
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
            'module_id' => 'required',
            'exercise_no' => 'required|numeric',
        ];

        if ($mode == 'edit') {
            foreach ($edit_rules as $field => $rule ) {
                $rules[$field] = $rule;
            }
        } else {
            // $rules['title'] = "required|unique:module_exercises,title,NULL,id,deleted,0";
        }

        return Validator::make($data, $rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $program_id, $module_id)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));

        $object = App::make("App\Http\Controllers\ModuleController");
        view()->share('programs', $object->ajaxModules($request));

        view()->share('title', trans("comman.module_exercise"));
        view()->share('program_id', $program_id);
        view()->share('module_id', $module_id);
        return view('module_exercise.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($program_id, $module_id, Request $request)
    {

        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $input = $request->all();

        $rules = array();
        $rules['title'] = [
                'required',
                'max:255',
                Rule::unique('module_exercises')->where(function ($query) use($module_id){
                    $query->where('module_id', $module_id)->where('deleted', '0');
                }),
            ];
        $this->validator($request->all(), 'edit', $rules)->validate();

        $file['reading_material'] = '';
        if ($request->hasFile('reading_material')) {
            $file['reading_material'] = \AppHelper::getUniqueFilename($request->file('reading_material'), AppHelper::path('uploads/module_excercise/')->getImagePath());
            $request->file('reading_material')->move(AppHelper::path('uploads/module_excercise/')->getImagePath(), $file['reading_material']);
            $input['reading_material'] = $file['reading_material'];
        }

        $model = ModuleExercise::create($input);
        if ($request->ajax()) {
            return response()->json([
                'success' => 'true',
                'data' => $model
            ]);
        }
        Flash::success(trans("comman.module_exercise_added"));

        if ($request->get('save_exit')) {
            return redirect(request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
        } else {
            return redirect()->route('module_exercise.create', ['program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), '_url' => request()->get('_url', route('modules.index', ['program_id' => Crypt::encryptString($program_id)]))]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($program_id, $module_id, $id)
    {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get("_url", route('modules.index', ['program_id' => $program_id])), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
            "add_question" => array("title" => '<b><i class="icon-diff-added"></i></b>Add Question', "url" => route('exercise_questions.create', ['program_id' => $program_id, 'module_id' => $module_id, 'exercise_id' => $id, '_url' => request()->getRequestUri()]), "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add Question')),
        ));

        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $id = Crypt::decryptString($id);

        $exercise = $this->findModuleExercise($module_id, $id);
           // dump($exercise);
        view()->share('program_id', $program_id);
        view()->share('module_id', $module_id);
        return view('module_exercise.show', compact('exercise'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $program_id
     * @param  int  $module_id
     * @param  int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($program_id, $module_id, $id, Request $request)
    {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get("_url", route('modules.index', ['program_id' => $program_id])), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));

        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $id = Crypt::decryptString($id);

        $module_exercise = ModuleExercise::find($id);
        if (is_null($module_exercise)) {
            return redirect(request()->get("_url", route('modules.index' , ['program_id' => Crypt::encryptString($program_id)])));
        }
        $object = App::make("App\Http\Controllers\ModuleController");
        view()->share('programs', $object->ajaxModules($request));

        view()->share('title', trans("comman.module_exercise"));
        view()->share('program_id', $program_id);
        view()->share('module_id', $module_id);
        view()->share('reading_material',$module_exercise->reading_material);
        return view('module_exercise.edit', compact('module_exercise'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($program_id, $module_id, $id, Request $request)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $id = Crypt::decryptString($id);
        $module_exercise = ModuleExercise::findOrFail($id);
        //dd($state);
        $input = AppHelper::getTrimmedData($request->all());
        $extra_rules = [];
        $extra_rules['title'] = [
                'required',
                'max:255',
                Rule::unique('module_exercises')->where(function ($query) use($module_id){
                    $query->where('module_id', $module_id)->where('deleted', '0');
                })->ignore($id),
            ];
        $this->validator($request->all(), 'edit', $extra_rules)->validate();
   $file['reading_material'] = '';
        if ($request->hasFile('reading_material')) {
            $file['reading_material'] = \AppHelper::getUniqueFilename($request->file('reading_material'), AppHelper::path('uploads/module_excercise/')->getImagePath());
            $request->file('reading_material')->move(AppHelper::path('uploads/module_excercise/')->getImagePath(), $file['reading_material']);
            $input['reading_material'] = $file['reading_material'];
        }

        $module_exercise->update($input);

        Flash::success(trans("comman.module_exercise_updated"));

        if ($request->get('save_exit')) {
            return redirect(request()->get('_url',route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
        } else {
            // return redirect()->route('module_exercise.edit', ['program_id' => $program_id ,'module_id' => $module_id ,'id' => $id]);
            return redirect(request()->get('_edit_url',route('modules.index', ['program_id' => Crypt::encryptString($program_id)])));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($program_id, $module_id, $id)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $id = Crypt::decryptString($id);
        $model = ModuleExercise::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->deleted = '1';
                $model->save();
                Flash::success(trans("comman.module_exercise_deleted"));
            }else {
                Flash::error(trans("comman.module_exercise_dependency_error",['dependency'=>$dependency]));
            }
        } else {
            Flash::error(trans("comman.module_exercise_error"));
        }
        // return redirect()->route('modules.index', ['program_id' => $program_id]);
        return back();
    }

    public function findModuleExercise($module_id, $id)
    {
        $module = Module::where('id', $module_id)->first()->toArray();
        $module['module_exercises'] = ModuleExercise::with(['module_exercise_questions' => function($query){
            $query->where('parent_question_id', '0');
            $query->orderBy('question_no');
        }, 'module_exercise_questions.sub_questions' => function($query){
            $query->orderBy('question_no');
        }])->where('id', $id)->orderby('exercise_no')->get();
        // dump($module); exit();
        // $res = DB::getQueryLog();
        // echo "<pre>";print_r(end($res));exit(0);
        return $module;
    }

    public function ajaxModuleExercises(Request $request) {
        // return Program::pluck('module_title', 'id')->toArray();
        $data = ModuleExercise::with(['module' => function($query) {
            $query->orderBy('module_no');
        }])->select(array(
            "module_exercises.*"
        ))->orderBy('module_exercises.exercise_no')->get();
        $module_exercises = array();
        foreach ($data as $row) {
            $module_exercises[$row->id] = $row->module->module_no . '.' . $row->exercise_no . ' ' . $row->title ;
        }
        return $module_exercises;
    }

    /*
     * Use Ajax Get All modules
     */

    public function ajaxAllModuleExercises(Request $request, $param = array()) {
        $model = ModuleExercise::with(['module' => function($query) {
            $query->orderBy('module_no');
        }])->select(array(
            "module_exercises.*"
        ))->orderBy('module_exercises.exercise_no');

        if ($request->get('module_id', false)) {
            $model->where('module_id', $request->get('module_id'));
        } elseif ($request->old('module_id', false) !== false) {
            $model->where('module_id', $request->old('module_id'));
        } elseif (!empty($param['module_id'])) {
            $model->where('module_id', $param['module_id']);
        }
        $data = $model->get();
        $module_exercises = array();
        foreach ($data as $row) {
            $module_exercises[$row->id] = $row->module->module_no . '.' . $row->exercise_no . ' ' . $row->title ;
        }
        return $module_exercises;
    }
}
