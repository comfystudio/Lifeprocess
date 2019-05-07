<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class AgentProgram extends MyModel {
	//
	protected $table = 'agent_program';

	protected $fillable = ['agent_id', 'program_id'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('agent_program.deleted', function (Builder $builder) {
			$builder->where('agent_program.deleted', '=', '0');
		});
	}

}
