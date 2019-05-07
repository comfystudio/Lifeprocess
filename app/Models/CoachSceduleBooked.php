<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CoachSceduleBooked extends MyModel {

	protected $table = 'coach_schedules_booked';

	protected $fillable = ['coach_schedules_id', 'booked_user_id', 'session_status','meeting_type','booked_for','booked_slot','deleted','cancel_reson'];

	protected $dependency = array(
		'CompletedCoachingSession' => ['field' => 'booked_schedule_id', 'model' => CompletedCoachingSession::class],
		'ClientScheduledSessionProblem' => ['field' => 'client_session_id', 'model' => ClientScheduledSessionProblem::class],
	);

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('coach_schedules_booked.deleted', function (Builder $builder) {
			$builder->where('coach_schedules_booked.deleted', '=', '0');
		});
	}
	public function client() {
		return $this->belongsTo(Client::class, 'booked_user_id', 'user_id');
	}
	public function coach_schedule() {
		return $this->belongsTo(CoachSchedule::class, 'coach_schedules_id')->withoutGlobalScopes();
	}
	public function coach_free_session() {
		return $this->belongsTo(CoachFreeSession::class, 'coach_schedules_id');
	}
	public function user() {
		return $this->belongsTo(User::class, 'booked_user_id');
	}
	public function problem_with_session() {
		return $this->belongsTo(ClientScheduledSessionProblem::class, 'id', 'client_session_id');
	}
	public function completed_session() {
		return $this->belongsTo(CompletedCoachingSession::class,'id','booked_schedule_id');
	}
}
