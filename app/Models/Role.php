<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Role extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['role_name', 'slug', 'permission'];
    protected $dependency = array(
        'User' => array('field' => 'role_id', 'model' => User::class),
    );

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('roles.deleted', function(Builder $builder) {
            $builder->where('roles.deleted', '=', '0');
        });
    }

    public function user()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
