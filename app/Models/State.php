<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class State extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['country_id', 'state'];
    protected $dependency = array(
        'User' => array('field' => 'state_id', 'model' => User::class),
    );

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('states.deleted', function(Builder $builder) {
            $builder->where('states.deleted', '=', '0');
        });
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
