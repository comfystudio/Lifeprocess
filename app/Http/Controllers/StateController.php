<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\State;
use AppHelper;
use Flash;
use Auth;
use DB;
use App;


class StateController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:states.create', ['only' => ['create', 'store']]);
        $this->middleware('check_for_permission.access:states.view', ['only' => ['index', 'show']]);
        $this->middleware('check_for_permission.access:states.update', ['only' => ['edit', 'update']]);
        $this->middleware('check_for_permission.access:states.delete', ['only' => ['destroy']]);
        $this->title = "States";
        view()->share('title', $this->title);
        //$this->ajax = new AjaxController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $action_nav = array(
            "add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> '.trans("comman.addstate"), "url" => route('states.create'),
                "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
        );

        if (!Auth::user()->hasAccess('states.create')) {
            unset($action_nav['add_new']);
        } 
        view()->share('module_action', $action_nav);
        view()->share('states', $this->get_index(array()));
        view()->share('counter', 0);
        return view('states.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => route('states.index'),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));
        $object = App::make("App\Http\Controllers\CountryController");
        view()->share('countries', $object->ajaxCountries($request));
        view()->share('title', trans("comman.state"));
        return view('states.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();

        $result = $this->validate($request, [
            'country_id' =>'required',
            'state' => "required|unique:states,state,NULL,id,deleted,0"] //,
            // [
            //     'country_id.required'=>trans("module_validation.country_required"),
            //     'state.required' => trans("module_validation.state_required"),
            //     'state.unique' => trans("module_validation.state_unique"),
            // ]
        );

        $model = State::create($input);
        if ($request->ajax()) {
            return response()->json([
                'success' => 'true',
                'data' => $model
            ]);
        }
        Flash::success(trans("comman.state_added"));

        //Event::fire(new AccountStateAdd($model));

        if ($request->get('save_exit')) {
            return redirect()->route('states.index');
        } else {
            return redirect()->route('states.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id) {
        $state = State::findOrFail($id);

        return view('states.show', compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id, Request $request) {
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => route('states.index'),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));

        $state = State::find($id);
        if (is_null($state)) {
            return redirect()->route('states.index');
        }
        $object = App::make("App\Http\Controllers\CountryController");
        view()->share('countries', $object->ajaxCountries($request));
        view()->share('title', trans("comman.state"));
        return view('states.edit', compact('state'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $state = State::findOrFail($id);
        //dd($state);
        $input = $request->all();
        //dd($input);
        $result = $this->validate($request, [
            'country_id' =>'required',
            'state' => "required|unique:states,state,{$id},id,deleted,0"] //,
            // [
            //     'country_id.required'=>trans("module_validation.country_required"),
            //     'state.required' => trans("module_validation.state_required"),
            //     'state.unique' => trans("module_validation.state_unique")
            // ]
        );

        $state->update($input);
        // $country = [
        //     'country_id' => $input['country_id'],
        // ];
        Flash::success(trans("comman.state_updated"));

        if ($request->get('save_exit')) {
            return redirect()->route('states.index');
        } else {
            return redirect()->route('states.edit', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id) {

        $model = State::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->deleted = '1';
                $model->save();
                Flash::success(trans("comman.state_deleted"));
            }else {
                Flash::error(trans("comman.state_dependency_error",['dependency'=>$dependency]));
            }
        } else {
            Flash::error(trans("comman.state_error"));
        }
        return redirect()->route('states.index');
    }

    // function to get the listing for the index page...
    public function get_index($sort_order)
    {
        $models = State::with('country')->where("states.deleted", "0");
        $models->select(array(
            "states.*"
        ));
        if (request()->get('state', false)) {
            $models->where('state', 'like', "%" . request()->get("state") . "%");
        }
        if (request()->get('country_id', false)) {
            $models->whereHas('country', function ($q) {
                $q->where('country', 'like', "%" . request()->get("country_id") . "%");
            });
        }
        
        if (!empty($sort_order) && is_array($sort_order)) {
            foreach ($sort_order as $column => $direction) {
                $models->orderBy($column, $direction);
            }
        } else {
            $models->orderBy('states.id', 'DESC');
        }

        return $models->get();

        // return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
    }

    public function ajaxStates(Request $request) {
        //echo "<pre>";print_r(State::where('lang','en')->pluck('state', 'id')->toArray());exit(0);
        return State::where('lang',Localization::getCurrentLocale())->pluck('state', 'id')->toArray();
    }

    /*
     * Use Ajax Get All States
     */

    public function ajaxAllStates(Request $request, $param = array()) {
        if ($request->get('country_id', false)) {
            return State::where('country_id', $request->get('country_id'))->pluck('state', 'id')->toArray();
        } elseif ($request->old('country_id', false) !== false) {
            return State::where('country_id', $request->old('country_id'))->pluck('state', 'id')->toArray();
        } elseif (!empty($param['country_id'])) {
            return State::where('country_id', $param['country_id'])->pluck('state', 'id')->toArray();
        }
        return array();
    }

    public function check_state() {

        $result = State::where('state','country_id')->where('deleted','0')->get()->toArray();
        if(empty($result)) {
            return false;
        }
        return true;
    }

    public function AllAjaxStates() {
        $state = State::pluck('state', 'id')->toArray();
        if(!empty($state)){
            return $state;
        }else{
            return array();
        }
    }
    /*
    ** Used In Form Fillup
    */
    public function ajaxLangStates(Request $request,$param = array()) {
        if($request->get('country')){
            return State::where('country_id', $request->get('country'))->pluck('state', 'id')->toArray();
        }
        return array();
    }
}
