<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Page extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pages';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'slug', 'content'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('pages.deleted', function (Builder $builder) {
			$builder->where('pages.deleted', '=', '0');
		});
	}
}
