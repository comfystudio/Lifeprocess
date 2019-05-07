<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\CoachNote;
use App;
use Auth;
use AppHelper;
use Flash;

class CoachNoteController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->title = trans('comman.coach_notes');
        $this->module_title = trans('comman.notes');
        view()->share('title', $this->title);
        view()->share('module_title', $this->module_title);
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
            'note' => 'required'
        ];
        $messages = [
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $client_id)
    {
        $client_id = Crypt::decryptString($client_id);
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url', route('client.detail', ['client_id' => $client_id])),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));
        view()->share('client_id', $client_id);
        view()->share('module_title', trans("comman.add_note"));
        return view('coach_notes.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $client_id)
    {
        $client_id = Crypt::decryptString($client_id);
        $input = AppHelper::getTrimmedData($request->all());
        $this->validator($request->all())->validate();

        $input['client_id'] = $client_id;
        $input['user_id'] = Auth::id();

        $model = CoachNote::create($input);
        if ($request->ajax()) {
            return response()->json([
                'success' => 'true',
                'data' => $model
            ]);
        }
        Flash::success(trans("comman.coach_note_added"));

        if ($request->get('save_exit')) {
            return redirect(request()->get('_url', route('client.detail', ['client_id' => Crypt::encryptString($client_id)])));
        } else {
            return redirect()->route('coach-notes.create', ['client_id' => Crypt::encryptString($client_id), '_url' => request()->get('_url', route('client.detail', ['client_id' => Crypt::encryptString($client_id)]))]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($client_id, $id)
    {
        $client_id = Crypt::decryptString($client_id);
        $id = Crypt::decryptString($id);
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url" => request()->get("_url", route('client.detail', ['client_id' => $client_id])),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));
        $coach_note = CoachNote::find($id);
        if (is_null($coach_note)) {
            return redirect(request()->get("_url", route('client.detail', ['client_id' => Crypt::encryptString($client_id)])));
        }

        view()->share('client_id', $client_id);
        return view('coach_notes.show', compact('coach_note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($client_id, $id)
    {
        $client_id = Crypt::decryptString($client_id);
        $id = Crypt::decryptString($id);

        $coach_note = CoachNote::find($id);
        if (is_null($coach_note)) {
            return redirect(request()->get("_url", route('client.detail', ['client_id' => Crypt::encryptString($client_id)])));
        }

        view()->share('client_id', $client_id);
        return view('coach_notes.edit', compact('coach_note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $client_id, $id)
    {
        $client_id = Crypt::decryptString($client_id);
        $id = Crypt::decryptString($id);

        $coach_note = CoachNote::findOrFail($id);
        $input = AppHelper::getTrimmedData($request->all());
        // dump($input); exit();
        $extra_rules = array(
        );
        $this->validator($request->all(), 'edit', $extra_rules)->validate();

        $coach_note->update($input);

        Flash::success(trans("comman.coach_note_updated"));

        if ($request->get('save_exit')) {
            return redirect(request()->get('_url',route('client.detail', ['client_id' => Crypt::encryptString($client_id)])));
        } else {
            return redirect()->route('coach-notes.edit', [Crypt::encryptString($id), 'client_id' => Crypt::encryptString($client_id), '_url' => request()->get('_url',route('client.detail', ['client_id' => Crypt::encryptString($client_id)]))]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // function to get the listing for the index page...
    public function get_index($filters = array(), $sort_order = array()) {
        //dd($filters['client_id']['value']);
        if(Auth::user()->user_type=='coach')
        {
            $models = CoachNote::with(['coach', 'client'])
            ->where('client_id', $filters['client_id']['value']);
        }
        else
        {
            $models = CoachNote::with(['coach', 'client'])
            ->where('user_id', Auth::id());
        }
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $column => $row) {
                if (!empty($column) && !empty($row["value"]) && is_array($row)) {
                    if ($row["operator"] == "like") {
                        $models->where("coach_notes." . $column, $row["operator"], "%" . $row["value"] . "%");
                    } else {
                        $models->where("coach_notes." . $column, $row["operator"], $row["value"]);
                    }
                }
            }
        }
        if (!empty($sort_order) && is_array($sort_order)) {
            foreach ($sort_order as $column => $direction) {
                $models->orderBy($column, $direction);
            }
        } else {
            $models->orderBy('coach_notes.id', 'DESC');
        }
        //dd($models->get());
        return $models->get();
    }
    public function ajaxnotes(Request $request, $param = array()){
       return $notes = CoachNote::where('user_id',$request->get('coach_id'))->where('client_id',$request->get('client_id'))->get()->toArray();
    }
}
