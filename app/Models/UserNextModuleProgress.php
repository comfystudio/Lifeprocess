<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserNextModuleProgress extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_next_module_progress';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id','start_datetime','end_datetime','billing_cycle','user_id'];


}
