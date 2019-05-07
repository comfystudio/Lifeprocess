<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Agent extends MyModel {

	protected $table = 'agents';

	protected $fillable = [
        'user_id', 'paypal_id', 'city', 'zip_code', 'biography', 'qualifications', 'experience', 'promotional_call', 'one_hour_session', 'free_20_min_session',
        'card_number', 'colour_1', 'colour_2', 'colour_3', 'colour_4', 'logo', 'pp_llpcoach_fast', 'pp_coach_fast', 'pp_llpcoach_normal', 'pp_coach_normal',
        'need_card', 'module_restriction', 'credits_per_month', 'credits_accumulate'
    ];

	// protected $dependency = array(
	// 	'Client' => array('field' => 'coach_id', 'model' => Client::class),
	// );

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('agents.deleted', function (Builder $builder) {
			$builder->where('agents.deleted', '=', '0');
		});
	}
	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function clients() {
		return $this->hasMany(User::class, 'created_by', 'user_id');
	}

	public function coaches() {
		return $this->hasMany(User::class, 'created_by', 'user_id');
	}

	public function programs() {
		return $this->belongsToMany(Program::class, 'agent_program');
	}

}
