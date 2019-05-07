<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Mylifestory extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mylifestory';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['message', 'created_user_id'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('mylifestory.deleted', function (Builder $builder) {
			$builder->where('mylifestory.deleted', '=', '0');
		});
	}
	public function user() {
		return $this->belongsTo(User::class, 'created_user_id');
	}

}
