<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Setting extends MyModel
{
    protected $table = 'settings';

    protected $fillable = ['name', 'title', 'value'];

    // protected $dependency = array(
    //  'Client' => array('field' => 'coach_id', 'model' => Client::class),
    // );

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('settings.deleted', function (Builder $builder) {
            $builder->where('settings.deleted', '=', '0');
        });
    }
}
