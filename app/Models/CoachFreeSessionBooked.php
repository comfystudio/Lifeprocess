<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CoachFreeSessionBooked extends MyModel {

	protected $table = 'coach_free_session_booked';

	protected $fillable = ['coach_schedules_id', 'booked_user_id', 'session_status','meeting_type'];


	protected static function boot() {
		parent::boot();

		static::addGlobalScope('coach_free_session_booked.deleted', function (Builder $builder) {
			$builder->where('coach_free_session_booked.deleted', '=', '0');
		});
	}
	public function client() {
		return $this->belongsTo(Client::class, 'booked_user_id', 'user_id');
	}
	public function coach_schedule() {
		return $this->belongsTo(CoachSchedule::class, 'coach_schedules_id');
	}
	public function coach_free_session() {
		return $this->belongsTo(CoachFreeSession::class, 'coach_schedules_id');
	}
	public function user() {
		return $this->belongsTo(User::class, 'booked_user_id');
	}

}
