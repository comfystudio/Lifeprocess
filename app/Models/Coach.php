<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Coach extends MyModel {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'coaches';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'agent_id', 'paypal_id', 'city', 'zip_code', 'biography', 'qualifications', 'experience', 'promotional_call', 'one_hour_session', 'free_20_min_session', 'available', 'balance', 'min_slots_availability_per_week','available_for_review','graduate_session','meeting_id','host_id','api_key','api_secret','zoom_email','active'];

	protected $dependency = array(
		'Client' => array('field' => 'coach_id', 'model' => Client::class),
		'Coach Notes' => array('field' => 'user_id', 'model' => CoachNote::class),
	);

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('coaches.deleted', function (Builder $builder) {
			$builder->where('coaches.deleted', '=', '0');
		});
	}
	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
	public function total_clients() {
		return count($this->hasMany(Client::class, 'coach_id'));
	}
	public function clients() {
		return $this->hasMany(Client::class, 'coach_id');
	}
	public function programs() {
		return $this->belongsToMany(Program::class, 'coach_program');
	}

	public function totalClients() {
		$data = $this->hasMany(Client::class, 'coach_id')->selectRaw('coach_id,count(*) as total_clients');
			if (request()->get('program', false) && request()->get('program') != '') {
				$data->where('clients.program_id',request()->get('program', false));
			}

		return $data->groupBy('coach_id');
	}
	public function totalOfClients() {
		$data = $this->hasOne(Client::class, 'coach_id')->selectRaw('coach_id,count(*) as total_clients');
			if (request()->get('program', false) && request()->get('program') != '') {
				$data->where('clients.program_id',request()->get('program', false));
			}
		$result = $data->groupBy('coach_id');
		return $result;
	}
	public function userFromStatus() {
		$data = $this->belongsTo(User::class, 'user_id');
		if (request()->get('status', false) && request()->get('status') != '') {
			$data->where('users.status',request()->get('status', false));
		}
		return $data;
	}
	public function coach_program()
	{
		return $this->hasMany(CoachProgram::class, 'coach_id');
	}
    public function agent() {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

}
