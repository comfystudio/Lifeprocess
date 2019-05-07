<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class BroadcastEmail extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'broadcast_emails';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['to', 'subject', 'message', 'is_sent'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
