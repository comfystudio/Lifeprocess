<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ClientScheduledSessionProblem extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'client_scheduled_session_problems';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_session_id', 'problem', 'other', 'created_at'];

    public $timestamps = false;
}
