<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Meeting extends MyModel {

	protected $table = 'meeting';

	protected $fillable = ['start_datetime','end_datetime','coach_id','client_id','meeting_id', 'coach_schedule_id'];

	// protected $dependency = array(
	// 	'Client' => array('field' => 'coach_id', 'model' => Client::class),
	// );

	protected static function boot() {
		parent::boot();
	}
}
