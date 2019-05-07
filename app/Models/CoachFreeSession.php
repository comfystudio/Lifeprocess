<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CoachFreeSession extends MyModel {
	protected $table = 'coach_free_session';

	protected $fillable = ['created_user_id', 'start_datetime', 'end_datetime','status'];

	protected $dependency = array(
		'Booked' => array('field' => 'coach_schedules_id', 'model' => CoachFreeSessionBooked::class),
	);

	/*
	*    Set CoachFreeSession start date as convert it from coach timezone to UTC
	*/
	public function setStartDatetimeAttribute($value)
	{
		$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['start_datetime'] = $date->toDateTimeString();
	}
	/*
	*    Set CoachFreeSession end date as convert it from coach timezone to UTC
	*/
	public function setEndDatetimeAttribute($value)
	{
		$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['end_datetime'] = $date->toDateTimeString();
	}/*
	*    get CoachFreeSession start date as convert it from coach timezone to UTC
	*/
	public function getStartDatetimeAttribute($value)
	{
		$user = User::where('id', \Auth::id())->first();
		// dump($user); exit();
		$current_user_timezone = 'UTC';
		if(!empty($user)) {
			$current_user_timezone = !empty($user->timezone) ? $user->timezone : 'UTC' ;
		}
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone($current_user_timezone);
		return $date->toDateTimeString();
	}
	/*
	*    get CoachFreeSession end date as convert it from coach timezone to UTC
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
		static::addGlobalScope('coach_free_session.deleted', function (Builder $builder) {
			$builder->where('coach_free_session.deleted', '=', \DB::raw("'0'"));
		});
	}
	public function coachfreesessionbooked() {
		return $this->belongsTo(CoachFreeSessionBooked::class, 'id', 'coach_schedules_id');
	}
	public function user() {
		return $this->belongsTo(User::class, 'created_user_id');
	}
	public function totalbooked() {
		return $this->belongsTo(CoachFreeSessionBooked::class, 'id', 'coach_schedules_id');
	}


}
