<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Client extends MyModel {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clients';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'agent_id', 'coach_id', 'contact_methods', 'credits', 'LPAP_initial_fee', 'program_id', 'coach_gender', 'kindle_email','module_restriction', 'invite_coach'];

	protected $dependency = array(
		// 'Coach Notes' => array('field' => 'client_id', 'model' => CoachNote::class),
	);

	protected static function boot() {
		parent::boot();


		static::addGlobalScope('clients.deleted', function (Builder $builder) {
			$builder->where('clients.deleted', '=', '0');
		});
	}

	/**
	 * Get the preffered contact methods exploding by comma (,)
	 *
	 * @param  string  $value
	 * @return array
	 */
	public function getContactMethodsAttribute($value) {
		if (!empty($value)) {
			return explode(",", $value);
		} else {
			return [];
		}
	}


	/**
	 * Set the preffered contact methods imploding by comma (,)
	 *
	 * @param  array  $value
	 * @return void
	 */
	public function setContactMethodsAttribute($value) {
		$this->attributes['contact_methods'] = implode(",", $value);
	}

	/**
	 * Set the Credits value to 0 if it is blank
	 *
	 * @param  array  $value
	 * @return void
	 */
	public function setCreditsAttribute($value) {
		$this->attributes['credits'] = ($value != '') ? $value : 0;
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
	public function carddetail() {
		return $this->hasOne(CardDetail::class, 'user_id');
	}
	public function coach() {
		return $this->belongsTo(Coach::class, 'coach_id');
	}
	public function program() {
		return $this->belongsTo(Program::class, 'program_id');
	}
	public function module_progress() {
		return $this->belongsToMany(Module::class, 'user_module_progresses', 'user_id');
	}
	public function schedule_booked() {
		return $this->hasMany(CoachSceduleBooked::class, 'booked_user_id', 'user_id');
	}
	public function schedule_booked_completed() {
		return $this->hasMany(CoachSceduleBooked::class, 'booked_user_id', 'user_id')->where('session_status', 'completed');
	}
	public function user_module_progresses() {
		return $this->belongsTo(UserModuleProgress::class, 'user_id', 'user_id')->whereNotNull('completed_at');
	}
	public function coachnote() {
		return $this->hasMany(CoachNote::class, 'client_id');
	}
    public function agent() {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

}
