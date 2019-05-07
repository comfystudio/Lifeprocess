<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends MyModel {

	// use \Venturecraft\Revisionable\RevisionableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'messages';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['messages', 'create_user_id', 'receive_user_id','is_read'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('messages.deleted', function (Builder $builder) {
			$builder->where('messages.deleted', '=', '0');
		});
	}

	public function user() {
		return $this->belongsTo(User::class, 'receive_user_id');
	}
    public function userCreator() {
        return $this->belongsTo(User::class, 'create_user_id');
    }
	public function attachment() {
		return $this->belongsTo(MessageAttachment::class, 'id','message_id');
	}

}
