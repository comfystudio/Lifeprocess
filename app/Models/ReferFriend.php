<?php

namespace App\Models;

class ReferFriend extends MyModel {
	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'refer_friends';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['create_user_id', 'use_your_name', 'name', 'email', 'friends_email', 'message'];

	public function user() {
		return $this->belongsTo(User::class, 'create_user_id');
	}

}
