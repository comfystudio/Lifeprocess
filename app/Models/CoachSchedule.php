<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CoachSchedule extends MyModel {
	protected $table = 'coach_schedules';

	protected $fillable = ['created_user_id', 'start_datetime', 'end_datetime','status','week_id','slot1','slot2','slot3','booked_for'];

	protected $dependency = array(
		'Booked' => array('field' => 'coach_schedules_id', 'model' => CoachSceduleBooked::class),
	);

	/*
	*    Set CoachSchedule start date as convert it from coach timezone to UTC
	*/
	public function setStartDatetimeAttribute($value)
	{
		$current_user_timezone = \Session::get('current_user_timezone', \Auth::user()->timezone);
		//$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['start_datetime'] = $date->toDateTimeString();
	}
	/*
	*    Set CoachSchedule end date as convert it from coach timezone to UTC
	*/
	public function setEndDatetimeAttribute($value)
	{
		$current_user_timezone = \Session::get('current_user_timezone', \Auth::user()->timezone);
		//$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['end_datetime'] = $date->toDateTimeString();
	}/*
	*    get CoachSchedule start date as convert it from coach timezone to UTC
	*/
	public function getStartDatetimeAttribute($value)
	{
        $current_user_timezone = \Session::get('current_user_timezone');
       // $current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);

		// $user = User::where('id', \Auth::id())->first();

		// $current_user_timezone = 'UTC';
		// if(!empty($user)) {
		// 	$current_user_timezone = !empty($user->timezone) ? $user->timezone : 'UTC' ;
		// }
		if(empty($current_user_timezone)){
			$current_user_timezone = 'UTC';
		}
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone($current_user_timezone);
		//dump($date);
		return $date->toDateTimeString();
	}
	/*
	*    get CoachSchedule end date as convert it from coach timezone to UTC
	*/
	public function getEndDatetimeAttribute($value)
	{
		$user = User::where('id', \Auth::id())->first();
		$current_user_timezone = 'UTC';
		if(!empty($user)) {
			$current_user_timezone = !empty($user->timezone) ? $user->timezone : 'UTC' ;
		}
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone($current_user_timezone);
		return $date->toDateTimeString();
	}
	protected static function boot() {
		parent::boot();
		static::addGlobalScope('coach_schedules.deleted', function (Builder $builder) {
			$builder->where('coach_schedules.deleted', '=', \DB::raw("'0'"));
		});
	}
	public function coachschedulebooked() {
		return $this->belongsTo(CoachSceduleBooked::class, 'id', 'coach_schedules_id');
	}
	public function user() {
		return $this->belongsTo(User::class, 'created_user_id');
	}
	public function totalbooked() {
		return $this->belongsTo(CoachSceduleBooked::class, 'id', 'coach_schedules_id');
	}


}
