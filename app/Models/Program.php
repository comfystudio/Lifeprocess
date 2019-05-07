<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Program extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'programs';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['program_name', 'status', 'program_fee', 'program_icon', 'sort_description', 'introduction_long_description','default_message','introduction_video','gratuate_video','stripe_program_name'];
	protected $dependency = array(
		'Module' => array('field' => 'program_id', 'model' => Module::class),
	);
	protected static function boot() {
		parent::boot();

		static::addGlobalScope('programs.deleted', function (Builder $builder) {
			$builder->where('programs.deleted', '=', '0');
		});
	}

	/**
	 * Get the modules for the program.
	 */
	public function modules() {
		return $this->hasMany(Module::class, 'program_id');
	}
public function coaches() {
		return $this->belongsToMany(Coach::class, 'coach_program');
	}
	public function coach_program() {
		return $this->hasMany(CoachProgram::class, 'program_id');
	}
	public function clients() {
		return $this->hasMany(Client::class, 'program_id');
	}
    public function user_program() {
        return $this->hasMany(UserProgram::class, 'program_id');
    }
}
