<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserModuleProgress extends MyModel {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_module_progresses';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'program_id', 'module_id', 'watch_video', 'read_material', 'completed_at', 'status', 'reviewed_at', 'reviewed_user_id', 'view_feedback_at', 'is_view_feedback_email_sent','is_submited_popup','is_last_module_review_email_send','is_first_module_email_send','module_exercise_id','is_gratuate_module'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('user_module_progresses.deleted', function (Builder $builder) {
			$builder->where('user_module_progresses.deleted', '=', '0');
		});
	}

	public function modules() {
		return $this->belongsTo(Module::class, 'module_id');
	}
	public function module_excercise(){
		return $this->belongsTo(ModuleExercise::class, 'module_exercise_id');
	}
	public function submittedBy() {
		return $this->belongsTo(User::class, 'user_id');
	}
	public function reviewedBy() {
		return $this->belongsTo(User::class, 'reviewed_user_id');
	}
	public function module_rate() {
		return $this->belongsTo(CoachModuleRate::class, 'module_id');
	}
	public function coach_transaction(){
		return $this->hasOne(CoachTransactionHistory::class, 'object_id','id');
	}



}
