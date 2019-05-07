<?php

namespace App\Http\Controllers;

use AppHelper;
use App\Models\ResourceLibrary;
use Auth;
use Cache;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class Clientresourcelibrary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
         parent::__construct();
        // $this->middleware('auth', ['except' => ['ajaxCoachPrograms']]);
        // $this->middleware('check_for_permission.access:programs.create', ['only' => ['create', 'store']]);
        // $this->middleware('check_for_permission.access:programs.view', ['only' => ['index', 'show']]);
        // $this->middleware('check_for_permission.access:programs.update', ['only' => ['edit', 'update']]);
        // $this->middleware('check_for_permission.access:programs.delete', ['only' => ['destroy']]);
        AppHelper::path('uploads/program/icons');
        $this->title = 'Resource Library';
        view()->share('title', $this->title);
    }
    public function index()
    {
        //
        $action_nav = array(
            "add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . 'Add New', "url" => route('resource_library.create', ['_url' => request()->getRequestUri()]), "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
        );
        // if (!Auth::user()->hasAccess('programs.create')) {
        //  unset($action_nav['add_new']);
        // }

        view()->share('programs', $this->get_index(array()));
        view()->share('module_action', $action_nav);
        view()->share('counter', 0);
        return view('resource_library.show');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $id = Crypt::decryptString($id);
        view()->share('module_action', array(
            "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('resource.show')),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
        ));
        $program = ResourceLibrary::find($id);
        if (is_null($program)) {
            return redirect(request()->get("_url", route('resource.show')));
        }

        return view('resource_library.view_resourcelibrary', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    public function get_index($filters = array(), $sort_order = array()) {
        $models = ResourceLibrary::where("resource_library.deleted", "0");
        $models->select(array(
            "resource_library.*",
        ));
        if (request()->get('name', false)) {
            $models->where('name', 'like', "%" . request()->get("name") . "%");
        }
        if (request()->get('status', false)) {
            $models->where('status', request()->get("status"));
        }

        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $column => $row) {
                if (!empty($column) && !empty($row["value"]) && is_array($row)) {
                    if ($row["operator"] == "like") {
                        $models->where("resource_library." . $column, $row["operator"], "%" . $row["value"] . "%");
                    } else {
                        $models->where("resource_library." . $column, $row["operator"], $row["value"]);
                    }
                }
            }
        }
        if (!empty($sort_order) && is_array($sort_order)) {
            foreach ($sort_order as $column => $direction) {
                $models->orderBy($column, $direction);
            }
        } else {
            $models->orderBy('resource_library.id', 'DESC');
        }
        $per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
        //return $models->get();
        // return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
    }
}
