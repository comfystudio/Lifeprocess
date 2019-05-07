<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CardDetail extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'card_details';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'card_type', 'card_number', 'expiry_date', 'CVV_number', 'card_issue_date', 'issue_number','card_holder'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('card_details.deleted', function(Builder $builder) {
            $builder->where('card_details.deleted', '=', '0');
        });
    }
    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
    public function clients() {
        return $this->belongsTo(Client::class, 'user_id');
    }
}

