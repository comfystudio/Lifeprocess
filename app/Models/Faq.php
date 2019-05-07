<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Faq extends MyModel {

	protected $table = 'faqs';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['role_id', 'question', 'answer'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('faqs.deleted', function (Builder $builder) {
			$builder->where('faqs.deleted', '=', '0');
		});
	}

}
