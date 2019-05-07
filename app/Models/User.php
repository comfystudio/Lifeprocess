<?php

namespace App\Models;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;
use DB;
use Cache;

class User extends MyModel {

    use Billable;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [

		 'name', 'email', 'gender', 'password', 'country_id', 'state', 'first_name', 'last_name', 'middle_name', 'mobile_no', 'address_line_one', 'address_line_two', 'address_line_three', 'image', 'role_id', 'username', 'timezone', 'skype_id', 'terms_and_condition', 'status', 'last_active', 'user_type', 'created_by', 'city', 'zip_code', 'paypal_billingAgreementID', 'subscription_plan_status','emergency_contact','date_of_birth', 'is_login', 'last_login', 'stripe_id', 'braintree_id', 'paypal_email', 'card_brand', 'card_last_four', 'trial_ends_at','welcome_message','gratuate_token','gratuate_option','gratuate_date','is_gratuate','is_gratuate_session_booked','is_unloack_module','is_booked_gratuate_session','unlocked_module','is_gratuate_question_asked','addedby','nextpaymentdate','is_free_session_complete','is_read_welcome_msg','registration_completed','deleted','stripe_sub_id','dont_show_dialog'];


	protected $dependency = array(
		'Coach' => array('field' => 'user_id', 'model' => Coach::class),
		'Client' => array('field' => 'user_id', 'model' => Client::class),
	);

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('users.deleted', function (Builder $builder) {
			$builder->where('users.deleted', '=', 0);
		});
	}
	public function getNameAttribute($value) {
		return  ucwords($value);
	}
    public function setNameAttribute($value) {
		$this->attributes['name'] =  ucwords($value);
	}
	public function role() {
		return $this->belongsTo(Role::class, 'role_id');
	}

	public static function hasAccess($permission) {
		$get_user_permission = Auth::user()->with('role')->where('id', Auth::user()->id)->first();
		if ($get_user_permission->role) {
			$getPermissionJsonToArray = (array) json_decode($get_user_permission->role->permission);
			if (isset($getPermissionJsonToArray[$permission]) && $getPermissionJsonToArray[$permission] == 1 && in_array($permission, $getPermissionJsonToArray)) {
				return true;
			}
			return false;
		} else {
			return false;
		}
	}

	public static function hasAnyAccess($permissions) {
		if (is_array($permissions)) {
			$getPermissionJsonToArray = array();
			$get_user_permission = Auth::user()->with('role')->where('id', Auth::user()->id)->first();
			if ($get_user_permission->role) {
				$getPermissionJsonToArray = (array) json_decode($get_user_permission->role->permission);
			}
			foreach ($permissions as $permission) {
				if (isset($getPermissionJsonToArray[$permission]) && $getPermissionJsonToArray[$permission] == 1 && in_array($permission, $getPermissionJsonToArray)) {
					return true;
				}
			}
		}
	}

	public function client() {
		return $this->hasOne(Client::class, 'user_id');
	}
	public function coach() {
		return $this->hasOne(Coach::class, 'user_id');
	}
    public function agent() {
        return $this->hasOne(Agent::class, 'user_id');
    }
	public function module_progress() {
		return $this->belongsToMany(Module::class, 'user_module_progresses', 'user_id')->withPivot('completed_at', 'status', 'reviewed_at', 'reviewed_user_id','module_exercise_id')->orderBy('reviewed_at', 'desc');
	}
	public function module_progress_excercise() {
		return $this->belongsToMany(ModuleExercise::class, 'user_module_progresses', 'user_id')->withPivot('completed_at', 'status', 'reviewed_at', 'reviewed_user_id','module_exercise_id')->with('module')	;
	}
	public function latest_module() {
		return $this->belongsToMany(Module::class, 'user_module_progresses', 'user_id')->withPivot('completed_at', 'status', 'reviewed_at', 'reviewed_user_id')->latest();
	}
	public function completed_modules() {
		return $this->belongsToMany(Module::class, 'user_module_progresses', 'user_id')->withPivot('completed_at', 'status', 'reviewed_at', 'reviewed_user_id')->wherePivot('completed_at', '!=', null);
	}
	public function client_module_exercise_answer() {
		return $this->belongsToMany(ModulesExercisesQuestion::class, 'user_modules_exercises_questions', 'user_id', 'question_id')->withPivot('answer', 'coach_respond', 'coach_respond_at');
	}
	public function send() {
		return $this->hasMany(Message::class, 'create_user_id', 'id')->latest('created_at','DESC');
	}
	public function receive() {
		return $this->hasMany(Message::class, 'receive_user_id', 'id')->latest('created_at','DESC');
	}

	public function submittedByClientYesterday() {

		if (request()->get('to_date') != '__/__/____') {
			$to_date = Carbon::parse(request()->get('to_date'))->subDays(1)->format('Y-m-d');
		} else {
			$to_date = Carbon::now()->subDays(1)->format('Y-m-d');
		}

		$data = $this->hasMany(UserModuleProgress::class, 'user_id')->selectRaw('user_id, count(*) as total_submitted')
			->where('completed_at', '!=', '')
			->where(\DB::raw("DATE(completed_at)"), $to_date);

		return $data->groupBy('user_id');
	}

	public function completed_modules_yesterday() {

		if (request()->get('to_date') != '__/__/____') {
			$to_date = Carbon::parse(request()->get('to_date'))->subDays(1)->format('Y-m-d');
		} else {
			$to_date = Carbon::now()->subDays(1)->format('Y-m-d');
		}

		return $this->belongsToMany(Module::class, 'user_module_progresses', 'user_id')
			->selectRaw('reviewed_user_id, count(*) as total_feedback')
			->where('user_module_progresses.status', 'reviewed')
			->where('reviewed_at', '!=', '')
			->where(\DB::raw("DATE(reviewed_at)"), $to_date)
			->withPivot('completed_at', 'status', 'reviewed_at', 'reviewed_user_id')
			->wherePivot('completed_at', '!=', null)
			->groupBy('reviewed_user_id');
	}

	public function submittedByClient14days() {

		if (request()->get('to_date') != '__/__/____') {
			$to_date = Carbon::parse(request()->get('to_date'))->subDays(14)->format('Y-m-d');
		} else {
			$to_date = Carbon::now()->subDays(14)->format('Y-m-d');
		}

		$data = $this->hasMany(UserModuleProgress::class, 'user_id')->selectRaw('user_id, count(*) as total_submitted1')
			->WhereNotNull('completed_at')
			->WhereNull('reviewed_at')
			->where(\DB::raw("DATE(completed_at)"), '>=', $to_date);

		return $data->groupBy('user_id');
	}

	public function submittedByClient21days() {

		if (request()->get('to_date') != '__/__/____') {
			$to_date1 = Carbon::parse(request()->get('to_date'))->subDays(14)->format('Y-m-d');
			$to_date2 = Carbon::parse(request()->get('to_date'))->subDays(21)->format('Y-m-d');
		} else {
			$to_date1 = Carbon::now()->subDays(14)->format('Y-m-d');
			$to_date2 = Carbon::now()->subDays(21)->format('Y-m-d');
		}

		$data = $this->hasMany(UserModuleProgress::class, 'user_id')->selectRaw('user_id, count(*) as total_submitted2')
			->WhereNotNull('completed_at')
			->WhereNull('reviewed_at')
			->where(\DB::raw("DATE(completed_at)"), '>=', $to_date2)
			->where(\DB::raw("DATE(completed_at)"), '<', $to_date1);

		return $data->groupBy('user_id');
	}

	public function submittedByClientBefore21days() {

		if (request()->get('to_date') != '__/__/____') {
			$to_date = Carbon::parse(request()->get('to_date'))->subDays(21)->format('Y-m-d');
		} else {
			$to_date = Carbon::now()->subDays(21)->format('Y-m-d');
		}

		$data = $this->hasMany(UserModuleProgress::class, 'user_id')->selectRaw('user_id, count(*) as total_submitted3')
			->WhereNotNull('completed_at')
			->WhereNull('reviewed_at')
			->where(\DB::raw("DATE(completed_at)"), '<', $to_date);

		return $data->groupBy('user_id');
	}

	public function coachschedulebooked() {
		$data = $this->hasMany(CoachSceduleBooked::class, 'booked_user_id')
			->selectRaw('coach_schedules_id,booked_user_id,count(*) as completed')
			->where('session_status', 'completed');
		return $data->groupBy('booked_user_id');
	}

	public function coachschedulenotbooked() {
		$data = $this->hasMany(CoachSceduleBooked::class, 'booked_user_id')
			->selectRaw('coach_schedules_id,booked_user_id,count(*) as uncompleted')
			->whereNull('session_status');
		return $data->groupBy('booked_user_id');
	}

	public function scheduled_sessions() {
		return $this->hasMany(CoachSchedule::class, 'created_user_id');
	}
	public function credit_card_detail() {
		return $this->hasOne(CardDetail::class, 'user_id');
	}
	public function transactionHistories()
	{
		return $this->hasMany(CoachTransactionHistory::class, 'user_id');
	}
	public function fail_transaction()
	{
		return $this->hasMany(CoachTransactionHistory::class,'user_id')->where('transaction_status','=','Failure');
	}
	public function cancel_transaction()
	{
		return $this->hasMany(CoachTransactionHistory::class,'user_id')->where('transaction_status','=','Cancelled');
	}
	public function credit_history() {
		return $this->hasMany(UserCreditsHistory::class,'user_id')->with(['creditpackage']);
	}
}
