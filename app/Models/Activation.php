<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Activation extends MyModel {

	protected $table = 'activations';

	protected $fillable = ['user_id', 'code', 'completed', 'completed_at'];

	// protected $dependency = array(
	// 	'Client' => array('field' => 'coach_id', 'model' => Client::class),
	// );

	protected static function boot() {
		parent::boot();
	}
}
