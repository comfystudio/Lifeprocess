<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class GratuateModuleProgress extends MyModel {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gratuate_module_progresses';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'program_id', 'module_id', 'watch_video', 'read_material', 'completed_at', 'status','deleted','created_at','updated_at','reviewed_at','reviewed_user_id'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('gratuate_module_progresses.deleted', function (Builder $builder) {
			$builder->where('gratuate_module_progresses.deleted', '=', '0');
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
