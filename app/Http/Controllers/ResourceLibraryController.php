<?php

namespace App\Http\Controllers;

use AppHelper;
use App\Models\ResourceLibrary;
use Auth;
use Cache;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ResourceLibraryController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		 parent::__construct();
		// $this->middleware('auth', ['except' => ['ajaxCoachPrograms']]);
		// $this->middleware('check_for_permission.access:programs.create', ['only' => ['create', 'store']]);
		// $this->middleware('check_for_permission.access:programs.view', ['only' => ['index', 'show']]);
		// $this->middleware('check_for_permission.access:programs.update', ['only' => ['edit', 'update']]);
		// $this->middleware('check_for_permission.access:programs.delete', ['only' => ['destroy']]);
		AppHelper::path('uploads/resource');
		$this->title = 'Resource Library';
		view()->share('title', $this->title);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . 'Add New', "url" => route('resource_library.create', ['_url' => request()->getRequestUri()]), "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		// if (!Auth::user()->hasAccess('programs.create')) {
		// 	unset($action_nav['add_new']);
		// }
		view()->share('module_action', $action_nav);
		view()->share('program_status', $this->program_status());
		view()->share('programs', $this->get_index(array()));
		view()->share('counter', 0);
		return view('resource_library.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get('_url', route('resource_library.index')), "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		view()->share('program_status', $this->program_status());
		return view('resource_library.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//print_r($_FILES);exit;
		//print_r($_REQUEST);exit;
		// echo "<pre>";
		// print_r($request->file('files')->getMimeType());exit;
		$input = $request->all();

		$result = $this->validate($request, [
			'name' => "required|unique:programs,program_name,NULL,id,deleted,0|max:255",
			'status' => 'required',
			'description' => 'required_without:files',
			'files' => 'required_without:description'
			//'description' => 'required',
			//'file' => 'image',
		],
		[
    			'description.required_without' => ' Please Select Description or Upload File',
    			'files.required_without' => ' Please Upload File or Select Description',

    		]
		);

		$file['files'] = '';
		$input['files'] = '';
		if ($request->hasFile('files')) {
			$file['files'] = \AppHelper::getUniqueFilename($request->file('files'), AppHelper::getImagePath());
			$file_type = $request->file('files')->getMimeType();
			$request->file('files')->move(AppHelper::getImagePath(), $file['files']);
			$input['files'] = $file['files'];
			$input['file_type'] = $file_type;
		}
		//print_r($input);exit;

		$model = ResourceLibrary::create($input);
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $model,
			]);
		}
		Flash::success('Record successfully added');

		if ($request->get('save_exit')) {
			// return redirect()->route('resource_library.index');
			return redirect(request()->get('_url', route('resource_library.index')));
		} else {
			// return redirect()->route('resource_library.create');
			return redirect()->route('resource_library.create', ['_url' => request()->get('_url', route('resource_library.index'))]);
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
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('resource_library.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$program = ResourceLibrary::find($id);
		if (is_null($program)) {
			return redirect(request()->get("_url", route('resource_library.index')));
		}

		view()->share('program_status', $this->program_status());
		return view('resource_library.edit', compact('program'));
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
		$resource_library = ResourceLibrary::findOrFail($id);

		$input = $request->all();

		$result = $this->validate($request, [
			'name' => "required|unique:programs,program_name,NULL,id,deleted,0|max:255",
			'status' => 'required',
			'description' => 'required_without:file_added',
			'files' => 'required_without_all:description,file_added'
			//'description' => 'required',
			//'file' => 'image',
		],
		[
    			'description.required_without' => ' Please Select Description or Upload File',
    			'files.required_without' => ' Please Upload File or Select Description',

    		]
		);
		//dump($input); exit();
		$file['files'] = '';
		if ($request->hasFile('files')) {
			$file['files'] = \AppHelper::getUniqueFilename($request->file('files'), AppHelper::getImagePath());
			$file_type = $request->file('files')->getMimeType();
			$request->file('files')->move(AppHelper::getImagePath(), $file['files']);
			$input['files'] = $file['files'];
			$input['file_type'] = $file_type;
		} else {
			$input['files'] = ($resource_library->files) ?: '';
		}

		$resource_library->update($input);

		Flash::success("record updated successfully");

		if ($request->get('save_exit')) {
			// return redirect(request()->get('_edit_url', route('resource_librarys.index')));
			return redirect(request()->get('_url', route('resource_library.index')));
		} else {
			return redirect()->route('resource_library.edit', [Crypt::encryptString($id), '_url' => request()->get('_url', route('resource_library.index'))]);
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
		$model = ResourceLibrary::find($id);
		// dd($model);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				Flash::success('Record Deleted Sucessfully');
			} else {
				Flash::error('Record cannot delete');
			}
		} else {
			Flash::error('Record cannot delete');
		}
		return back();
		// return redirect()->route('programs.index');
	}

	// function to get the listing for the index page...
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
}
