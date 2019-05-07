<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Permissions\Permissions;
use Illuminate\Http\Request;
use App\Models\Role;
use AppHelper;
use Flash;
use Auth;
use Cache;

class RoleController extends Controller {

    /** @var Cartalyst\Sentinel\Users\IlluminateRoleRepository */
    protected $roleRepository;

    public function __construct() {
        parent::__construct();
        $this->permission = new Permissions;

        // Middleware
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:roles.create', ['only' => ['create', 'store']]);
        $this->middleware('check_for_permission.access:roles.view', ['only' => ['index', 'show']]);
        $this->middleware('check_for_permission.access:roles.update', ['only' => ['edit', 'update']]);
        $this->middleware('check_for_permission.access:roles.delete', ['only' => ['destroy']]);

        view()->share('module_title', 'Roles');
        view()->share('title', trans("comman.role"));
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
        ];

        if ($mode == 'edit') {
            foreach ($edit_rules as $field => $rule ) {
                $rules[$field] = $rule;
            }
        } else {
            $rules['role_name'] = [
                'required',
                'max:191',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ];
            $rules['slug'] = [
                'required',
                'max:191',
                'alpha_dash',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ];
        }
        return Validator::make($data, $rules);
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $action_nav = array(
            "add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> '.trans("comman.add_role"), "url" => route('roles.create'), "attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
        );
        if (!Auth::user()->hasAccess('roles.create')) {
            unset($action_nav['add_new']);
        }        
        view()->share('roles', $this->get_index(array()));
        view()->share('module_action',$action_nav);
        return view('roles.index');
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        view()->share('module_title', 'Add Roles');
        view()->share('module_action', array(
               "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url"=>route('roles.index'),
               "attributes"=> array("class" => "btn bg-blue btn-labeled heading-btn",'title'=>'Back')),
           ));
        return view('roles.create',[
            'all_permission' => $this->getPermissionArrayToNameWise((new Permissions)->getPermissions()),
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Validate the form data
        $input = AppHelper::getTrimmedData($request->all());

        $extra_rules = array(
            'role_name' => [
                'required',
                'max:191',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ]
        );
        $this->validator($request->all(), 'edit', $extra_rules)->validate();
        // // Create the Role
        // $role = Sentinel::getRoleRepository()->createModel()->create([
        //     'role_name' => trim($request->get('role_name')),
        // ]);

        // Cast permissions values to boolean
        $permissions = [];
        /*if(isset($input['permission'])) {
            foreach ($input['permission'] as $permission => $value) {
                foreach ($value as $key => $val) {
                    $permissions[base64_decode($key)] = (bool) $val;
                }
            }
            $input['permission'] = json_encode($permissions);
        }*/
        if(isset($input['hdn_permission']) && isset($input['permission'])) {
            foreach ($input['hdn_permission'] as $hdn_permission => $value) {
                foreach ($value as $key => $val) {
                    $permission_value = (array_get($input['permission'], $hdn_permission. '.' . $key));
                    $permissions[base64_decode($key)] = (bool) $permission_value;
                }
            }
            $input['permission'] = json_encode($permissions);
        }

        $role = Role::create($input);

        // // Set the role permissions
        // $role->permissions = $permissions;
        // $role->save();

        // All done
        if ($request->ajax()) {
            return response()->json(['role' => $role], 200);
        }
        // session()->flash('success', trans("comman.role").' '. "'{$role->name}' ".' '.trans("comman.created"));
        Flash::success(trans("comman.role_added"));
        if($request->get('save_exit')){
            return redirect()->route('roles.index');
        }else{
            return redirect()->route('roles.create');
        }

    }

    /**
     * Display the specified role.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        // The roles detail page has not been included for the sake of brevity.
        // Change this to point to the appropriate view for your project.
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $id = Crypt::decryptString($id);
        view()->share('module_title', 'Edit Roles');
        view()->share('module_action', array(
               "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> '.trans("comman.back"), "url"=>route('roles.index'),
               "attributes"=> array("class" => "btn bg-blue btn-labeled heading-btn",'title'=>'Back')),
           ));
        // Fetch the role object
        // $id = $this->decode($hash);
        // $role = $this->roleRepository->findById($id);

        $role = Role::find($id);
        if ($role) {
            $users_permission = $this->getPermissionJsonToArray(json_decode($role->permission));
            $all_permission = $this->getPermissionArrayToNameWise((new Permissions)->getPermissions());
            return view('roles.edit', compact('role', 'users_permission', 'all_permission'));
        }
        Flash::error(trans("comman.invalid_role"));
        return redirect()->back();
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // Decode the role id
        $id = Crypt::decryptString($id);
        // Validate the form data
        $input = $request->all();

        $extra_rules = array(
            'role_name' => [
                'required',
                'max:191',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted', '0');
                })->ignore($id),
            ],
            'slug' => [
                'required',
                'max:191',
                'alpha_dash',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted', '0');
                })->ignore($id),
            ]
        );
        $this->validator($request->all(), 'edit', $extra_rules)->validate();

        // Fetch the role object
        // $role = $this->roleRepository->findById($id);
        $role = Role::findOrFail($id);

        if (!$role) {
            if ($request->ajax()) {
                return response()->json(trans("comman.invalid_role"), 422);
            }
            Flash::error(trans("comman.invalid_role"));
            return redirect()->back()->withInput();
        }

        // Update the role
        $role->role_name = trim($request->get('role_name'));
        $role->slug = trim($request->get('slug'));

        // Cast permissions values to boolean
        $permissions = [];
        if(isset($input['hdn_permission']) &&  isset($input['permission'])) {
            foreach ($input['hdn_permission'] as $hdn_permission => $value) {
                foreach ($value as $key => $val) {
                    $permission_value = (array_get($input['permission'], $hdn_permission. '.' . $key));
                    $permissions[base64_decode($key)] = (bool) $permission_value;
                }
            }
        }
        // Set the role permissions
        $role->permission = json_encode($permissions);
        $role->save();

        // All done
        if ($request->ajax()) {
            return response()->json(['role' => $role], 200);
        }

        Flash::success(trans("comman.role_updated"));
        if($request->get('save_exit')){
            return redirect()->route('roles.index');
        }else{
            return redirect()->route('roles.edit', Crypt::encryptString($id));
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        // Fetch the role object
        // $role = $this->roleRepository->findById($id);

        // Remove the role
        // $role->delete();

        // // All done
        // $message = trans("comman.role").' '."'{$role->name}'".' '.trans("comman.removed");
        // if ($request->ajax()) {
        //     return response()->json([$message], 200);
        // }

        // session()->flash('success', $message);
        // return redirect()->route('roles.index');

        $id = Crypt::decryptString($id);
        $model = Role::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->deleted = '1';
                $model->save();
                Flash::success(trans("comman.role_deleted"));
            }else {
                Flash::error(trans("comman.role_dependency_error",['dependency'=>$dependency]));
            }
        } else {
            Flash::error(trans("comman.role_error"));
        }
        return redirect()->route('roles.index');
    }

    /**
     * Uses to set name wise array to permission base array
     * INPUT = 'users'=> [ 'users.create', 'users.update', 'users.view', 'users.destroy']
     * OUTPUT = 'users'=> [ 'create', 'update', 'view', 'destroy']
     * @param1 array
     * @return array
     * @uses PermissionController,EmployeeController
     */
    public function getPermissionArrayToNameWise($permission = []) {
        $data = [];
        if(!empty($permission)) {
            foreach ($permission as $permission_key => $permission_array) {
                foreach ($permission_array as $permission_name => $permission_value) {
                    $permi = explode('.', $permission_value);
                    $data[$permi[0]][$permission_name] = array(
                        'permission' => base64_encode($permission_value),
                        'label' => $permi[1],
                        'can_inherit' => -1,
                    );
                    //$data[$permi[0]][$permi[1]] = -1; //inherit
                    //$data[$permi[0]][$permi[1]] = base64_encode($permi[1]); //inherit
                }
            }
        }
        return $data;
    }

    /**
     * Uses to set name wise JSON to permission base array
     * INPUT = [ 'users.create', 'users.update', 'users.view', 'users.destroy']
     * OUTPUT = 'users'=> [ 'create', 'update', 'view', 'destroy']
     * @param1 array
     * @return array
     * @uses PermissionController,EmployeeController
     */
    public static function getPermissionJsonToArray($permission = []) {
        $data = [];$i=0;
        if(!empty($permission)) {
            foreach ($permission as $permission_key => $permission_value) {
                $permi = explode('.', $permission_key);
                $data[($permi[0])][$permi[1]] = $permission_value;
            }
        }
        // echo "<pre>";print_r($data);exit(0);
        return $data;
    }

    // function to get the listing for the index page...
    public function get_index($sort_order)
    {
        $models = Role::with('user')->where("roles.deleted", "0");
        $models->select(array(
            "roles.*"
        ));
        if(request()->get('role_name', false)) {
            $models->where('role_name', 'like', '%' . request()->get('role_name') . '%');
        }
        if (!empty($sort_order) && is_array($sort_order)) {
            foreach ($sort_order as $column => $direction) {
                $models->orderBy($column, $direction);
            }
        } else {
            $models->orderBy('roles.id', 'DESC');
        }
        $per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
        //return $models->get();

        // return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
    }

    public function getAllRoles()
    {
        $models = Role::where("roles.deleted", "0")->select(array(
            "roles.id",
            "roles.role_name"
        ))->orderBy('roles.id')->get()->toArray();
        return $models;
    }
}
