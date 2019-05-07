<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Week extends MyModel {
	protected $table = 'week';

	protected $fillable = ['created_user_id', 'start_datetime', 'end_datetime','status'];

	/*
	*    Set Week start date as convert it from coach timezone to UTC
	*/
	public function setStartDatetimeAttribute($value)
	{
		$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['start_datetime'] = $date->toDateTimeString();
	}
	/*
	*    Set Week end date as convert it from coach timezone to UTC
	*/
	public function setEndDatetimeAttribute($value)
	{
		$current_user_timezone = (User::where('id', \Auth::id())->first()->timezone);
		$timestamp = $value;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $current_user_timezone)->setTimezone('UTC');
		$this->attributes['end_datetime'] = $date->toDateTimeString();
	}/*
	*    get Week start date as convert it from coach timezone to UTC
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
	*    get Week end date as convert it from coach timezone to UTC
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
		static::addGlobalScope('week.deleted', function (Builder $builder) {
			$builder->where('week.deleted', '=', \DB::raw("'0'"));
		});
	}
	public function user() {
		return $this->belongsTo(User::class, 'created_user_id');
	}


}
