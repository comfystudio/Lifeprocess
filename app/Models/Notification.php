<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Notification extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['notification_text'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('notifications.deleted', function(Builder $builder) {
            $builder->where('notifications.deleted', '=', '0');
        });
    }

    // get all notification users related to this model
    public function notify_user()
    {
        return $this->hasMany(NotifyUser::class, 'notification_id');
    }
}
