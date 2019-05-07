<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Country extends MyModel {

    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['country', 'country_code'];
    
    protected $dependency = array(
        'State' => array('field' => 'country_id', 'model' => State::class),
        'User' => array('field' => 'country_id', 'model' => User::class),
    );

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('countries.deleted', function(Builder $builder) {
            $builder->where('countries.deleted', '=', '0');
        });
    }

    // set country code in uppercase letters...
    public function setCountryCodeAttribute($value)
    {
        $this->attributes['country_code'] = strtoupper($value);
    }
}
