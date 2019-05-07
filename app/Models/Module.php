<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Module extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'modules';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['program_id', 'module_title', 'module_no', 'introduction_video', 'delay_btw_chapter_exercise', 'reading_material', 'status', 'default_rate'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('modules.deleted', function (Builder $builder) {
			$builder->where('modules.deleted', '=', '0');
		});
	}

	public function program() {
		return $this->belongsTo(Program::class, 'program_id');
	}
	public function module_exercises() {
		return $this->hasMany(ModuleExercise::class, 'module_id');
	}
	public function module_rate() {
		return $this->belongsTo(CoachModuleRate::class, 'module_id');
	}
}
