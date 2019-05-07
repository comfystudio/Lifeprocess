<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ResourceLibrary extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'resource_library';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'status', 'files', 'description','file_type'];
	// protected $dependency = array(
	// 	'Module' => array('field' => 'program_id', 'model' => Module::class),
	// );
	protected static function boot() {
		parent::boot();

		static::addGlobalScope('resource_library.deleted', function (Builder $builder) {
			$builder->where('resource_library.deleted', '=', '0');
		});
	}

	/**
	 * Get the modules for the program.
	 */
	// public function modules() {
	// 	return $this->hasMany(Module::class, 'program_id');
	// }
	// public function coaches() {
	// 	return $this->belongsToMany(Coach::class, 'coach_program');
	// }
	// public function coach_program() {
	// 	return $this->hasMany(CoachProgram::class, 'program_id');
	// }
	// public function clients() {
	// 	return $this->hasMany(Client::class, 'program_id');
	// }
}
