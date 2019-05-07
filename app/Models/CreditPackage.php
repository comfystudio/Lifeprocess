<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class CreditPackage extends MyModel {
	protected $table = 'credit_packages';
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $fillable = ['credit', 'price', 'status'];
	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected static function boot() {
		parent::boot();

		static::addGlobalScope('credit_packages.deleted', function (Builder $builder) {
			$builder->where('credit_packages.deleted', '=', '0');
		});
	}
	// public function credit_history() {
	// 	return $this->belongsTo(UserCreditsHistory::class,'credit_score');
	// }
}
