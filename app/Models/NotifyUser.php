<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class NotifyUser extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notify_users';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['receiver_id', 'notification_id', 'read'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('notify_users.deleted', function(Builder $builder) {
            $builder->where('notify_users.deleted', '=', '0');
        });
    }

    // get all notification related to this model
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}
