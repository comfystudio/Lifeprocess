<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Models\ModulesExercisesQuestion;
use App\Models\ModulesExercisesQuestionsOption;
use App\Models\ModuleExercise;
use AppHelper;
use Flash;
use Auth;
use DB;
use App;

class ModulesExercisesQuestionController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->title = trans('comman.exercise_question');
        view()->share('title', $this->title);
        //$this->ajax = new AjaxController();
    }

    /**
     * Get a validator for an incoming creating/updating request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $mode = 'create', $edit_rules = array(), $messages = array()) // $mode = create / edit
    {
        $rules = [
            'module_id' => 'required',
            'module_exercise_id' => 'required',
            'question_title' => 'required',
            'question_no' => 'required|integer|min:0',
            'answer_format' => 'required'
        ];

        // dump($messages); die();
        if ($mode == 'edit') {
            foreach ($edit_rules as $field => $rule ) {
                $rules[$field] = $rule;
            }
        } else {
            // $rules['title'] = "required|unique:module_exercises,title,NULL,id,deleted,0";
        }

        return Validator::make($data, $rules, $messages);
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
    public function create(Request $request, $program_id, $module_id, $exercise_id)
    {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get('_url', route('module_exercise.show', ['program_id' => $program_id, 'module_id' => $module_id, 'id' => $exercise_id])), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));

        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);

        $object = App::make("App\Http\Controllers\ModuleController");
        view()->share('programs', $object->ajaxModules($request));

        $object = App::make("App\Http\Controllers\ModuleExerciseController");
        view()->share('exercises', $object->ajaxAllModuleExercises($request, array('module_id' => $module_id)));

        view()->share('parent_questions', $this->getModulesQuestions($request, array('module_exercise_id' => $exercise_id)));
        view()->share('answer_format', $this->answer_format());
        view()->share('title', trans("comman.exercise_question"));
        view()->share('program_id', $program_id);
        view()->share('module_id', $module_id);
        view()->share('exercise_id', $exercise_id);
        return view('exercise_questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($program_id, $module_id, $exercise_id ,Request $request)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);

        $input = AppHelper::getTrimmedData($request->all());
        $rules = [];
        $messages = [];
        if ($input['answer_format'] == 'rank') {
            $rules['items.*.option_value'] = 'required|max:255';
            $messages['items.*.option_value.required'] = 'The option field is required';
            $messages['items.*.option_value.max'] = 'The option may not be greater than 255 characters.';
        }else if ($input['answer_format'] == 'plain_text') {
            $rules['min_value'] = 'required|integer|min:0';
            $rules['max_value'] = 'required|integer|min:1';
        }
        else if($input['answer_format']=='slider')
        {
            $rules['min_range_value'] = 'required|integer|min:0';
            $rules['max_range_value'] = 'required|integer|min:1';
        }
        $this->validator($request->all(), 'edit', $rules, $messages)->validate();

        // if (isset($input['parent_question_id']) && $input['parent_question_id'] == '') {
        //     $input['parent_question_id'] = 0;
        // } else if(!isset($input['parent_question_id'])){
        //     $input['parent_question_id'] = 0;
        // }

        DB::transaction(function () use($input, $request) {
            $model = ModulesExercisesQuestion::create($input);
            $question_id = $model->id;

            if($input['answer_format'] == 'rank') {
                foreach ($input['items'] as $item) {
                    if(!empty($item['option_value'])) {
                        ModulesExercisesQuestionsOption::create(array("question_id" => $question_id, "option_value" => $item['option_value']));
                    }
                }
            }
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'true',
                    'data' => $model
                ]);
            }
            Flash::success(trans("comman.module_exercise_question_added"));
        });

        if ($request->get('save_exit')) {
            // return redirect()->route('module_exercise.show', ['program_id' => $program_id,'module_id' => $module_id, 'id' => $exercise_id]);
            return redirect(request()->get('_url', route('module_exercise.show', ['program_id' => Crypt::encryptString($program_id),'module_id' => Crypt::encryptString($module_id), 'id' => Crypt::encryptString($exercise_id)])));
        } else {
            // return redirect()->route('exercise_questions.create', ['program_id' => $program_id, 'module_id' => $module_id, 'exercise_id' => $exercise_id]);
            return redirect()->route('exercise_questions.create', ['program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id), '_url' => request()->get('_url', route('module_exercise.show', ['program_id' => Crypt::encryptString($program_id),'module_id' => Crypt::encryptString($module_id), 'id' => Crypt::encryptString($exercise_id)]))]);
        }
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($program_id, $module_id, $exercise_id, $id, Request $request)
    {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get("_url", route('module_exercise.show', ['program_id' => $program_id,'module_id' => $module_id, 'id' => $exercise_id])), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));

        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);
        $id = Crypt::decryptString($id);

        $question = ModulesExercisesQuestion::find($id);
        if (is_null($question)) {
            return redirect(request()->get("_url", route('module_exercise.show', ['program_id' => Crypt::encryptString($program_id),'module_id' => Crypt::encryptString($module_id), 'id' => Crypt::encryptString($exercise_id)])));
        }
        $question['items'] = ModulesExercisesQuestionsOption::where("question_id", $id)->get()->toArray();

        $object = App::make("App\Http\Controllers\ModuleController");
        view()->share('programs', $object->ajaxModules($request));

        $object = App::make("App\Http\Controllers\ModuleExerciseController");
        view()->share('exercises', $object->ajaxAllModuleExercises($request, array('module_id' => $module_id)));

        $parent_questions = $this->getModulesQuestions($request, array('module_exercise_id' => $exercise_id, 'current_question_id' => $id));
        view()->share('parent_questions', $parent_questions);
        view()->share('answer_format', $this->answer_format());

        view()->share('title', trans("comman.exercise_question"));
        view()->share('program_id', $program_id);
        view()->share('module_id', $module_id);
        view()->share('exercise_id', $exercise_id);
        return view('exercise_questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($program_id, $module_id, $exercise_id, $id, Request $request)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);
        $id = Crypt::decryptString($id);

        $question = ModulesExercisesQuestion::findOrFail($id);
        //dd($state);
        $input = AppHelper::getTrimmedData($request->all());
        $extra_rules = [];
        $messages = [];
        if ($input['answer_format'] == 'rank') {
            $extra_rules['items.*.option_value'] = 'required|max:255';
            $messages['items.*.option_value.required'] = 'The option field is required';
            $messages['items.*.option_value.max'] = 'The option may not be greater than 255 characters.';
        } else if ($input['answer_format'] == 'plain_text') {
            $extra_rules['min_value'] = 'required|integer|min:0';
            $extra_rules['max_value'] = 'required|integer|min:1';
        }
        $this->validator($request->all(), 'edit', $extra_rules, $messages)->validate();

        // if (isset($input['parent_question_id']) && $input['parent_question_id'] == '') {
        //     $input['parent_question_id'] = 0;
        // } else if(!isset($input['parent_question_id'])){
        //     $input['parent_question_id'] = 0;
        // }

        DB::transaction(function () use($input, $question, $id) {
            $question->update($input);
            if($input['answer_format'] == 'rank') {
                if (!empty($input['items']) && isset($input['items'])) {
                    ModulesExercisesQuestionsOption::where('question_id', $id)->update(['deleted' => '1']);
                    foreach ($input['items'] as $key => $value) {
                        $sub_array = array();
                        $sub_array['question_id'] = $id;
                        $sub_array['option_value'] = $value['option_value'];
                        $sub_array['deleted'] = '0';
                        if(isset($value['id']) && !empty($value['id'])) {
                            ModulesExercisesQuestionsOption::withoutGlobalScope('modules_exercises_questions_options.deleted')->where('id',$value['id'])->update($sub_array);
                        } else {
                            ModulesExercisesQuestionsOption::create($sub_array);
                        }
                    }
                }
            }
            Flash::success(trans("comman.module_exercise_question_updated"));
        });

        if ($request->get('save_exit')) {
            // return redirect()->route('module_exercise.show', ['program_id' => $program_id,'module_id' => $module_id, 'id' => $exercise_id]);
            return redirect(request()->get('_url',route('module_exercise.show', ['program_id' => Crypt::encryptString($program_id),'module_id' => Crypt::encryptString($module_id), 'id' => Crypt::encryptString($exercise_id)])));
        } else {
            // return redirect()->route('exercise_questions.edit', ['program_id' => $program_id ,'module_id' => $module_id, 'exercise_id' => $exercise_id ,'id' => $id]);
            return redirect(request()->get('_edit_url',route('module_exercise.show', ['program_id' => Crypt::encryptString($program_id),'module_id' => Crypt::encryptString($module_id), 'id' => Crypt::encryptString($exercise_id)])));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($program_id, $module_id, $exercise_id, $id)
    {
        $program_id = Crypt::decryptString($program_id);
        $module_id = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);
        $id = Crypt::decryptString($id);

        $model = ModulesExercisesQuestion::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                DB::transaction(function () use($model, $id) {
                    $model->deleted = '1';
                    $model->save();
                    ModulesExercisesQuestionsOption::where('question_id', $id)->update(['deleted' => '1']);
                    Flash::success(trans("comman.module_exercise_question_deleted"));
                });
            }else {
                Flash::error(trans("comman.module_exercise_question_dependency_error",['dependency'=>$dependency]));
            }
        } else {
            Flash::error(trans("comman.module_exercise_question_error"));
        }
        // return redirect()->route('module_exercise.show', ['program_id' => $program_id,'module_id' => $module_id, 'id' => $exercise_id]);
        return back();
    }

    public function getModulesQuestions(Request $request, $param = array())
    {
        $questions = ModuleExercise::with(['module_exercise_questions' => function($query) use($request, $param){
            $query->where('parent_question_id', '0');
            if ($request->get('current_question_id', false)) {
                $query->where('id', '!=' ,$request->get('current_question_id'));
            } elseif ($request->old('current_question_id', false) !== false) {
                $query->where('id', '!=' ,$request->old('current_question_id'));
            } elseif (!empty($param['current_question_id'])) {
                $query->where('id', '!=' ,$param['current_question_id']);
            }
            $query->orderBy('question_no');
        }, 'module_exercise_questions.sub_questions' => function($query) use($request, $param){
            /*if ($request->get('current_question_id', false)) {
                $query->where('id', '!=' ,$request->get('current_question_id'));
            } elseif ($request->old('current_question_id', false) !== false) {
                $query->where('id', '!=' ,$request->old('current_question_id'));
            } elseif (!empty($param['current_question_id'])) {
                $query->where('id', '!=' ,$param['current_question_id']);
            }*/
            $query->orderBy('question_no');
        }]);
        if ($request->get('module_exercise_id', false)) {
            $questions->where('id', $request->get('module_exercise_id'));
        } elseif ($request->old('module_exercise_id', false) !== false) {
            $questions->where('id', $request->old('module_exercise_id'));
        } elseif (!empty($param['module_exercise_id'])) {
            $questions->where('id', $param['module_exercise_id']);
        }

        $data = $questions->get();
        $module_exercise_questions = array();
        foreach ($data as $row) {
            foreach ($row->module_exercise_questions as $question) {
                $module_exercise_questions[$question->id] = $question->question_no . '. ' . $question->question_title ;
                if (count($question->sub_questions)) {
                    $module_exercise_questions += $this->createMultilevelQuestionsList($question->sub_questions, $question->question_no, '2');
                }
                // foreach ($question->sub_questions as $sub_question) {
                //     $module_exercise_questions[$sub_question->id] = ' -- ' . $question->question_no . '.' . $sub_question->question_no . '. ' . $sub_question->question_title ;
                // }
            }
        }
        // dump($module_exercise_questions); exit();
        return $module_exercise_questions;
    }
    function createMultilevelQuestionsList($sub_questions, $parent_question_no, $level) {
        $arr = [];
        foreach ($sub_questions as $sub_question) {
            $question_no = $parent_question_no . '.' . $sub_question->question_no;
            $arr[$sub_question->id] = ' ' . str_repeat('-', $level) . ' ' . $question_no . '. ' . $sub_question->question_title ;
            if(count($sub_question->sub_questions)) {
                $arr += $this->createMultilevelQuestionsList($sub_question->sub_questions, $question_no, $level+1);
            }
        }
        return $arr;
    }
    public function answer_format()
    {
        $format = [
            'plain_text' => trans('comman.plain_text'),
            'boolean_yes_no' => trans('comman.boolean_yes_no'),
            'rank' => 'Rank(Radio-button)',
            'statement'=>'Statement',
            'slider'=>'Slider',
        ];
        return $format;
    }
}
