<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserCreditsHistory extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_credits_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'object_id', 'object_type', 'transaction_type', 'credit_score','payment_type'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('user_credits_histories.deleted', function (Builder $builder) {
            $builder->where('user_credits_histories.deleted', '=', '0');
        });
    }

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    // protected $touches = ['user_module_progresses', 'coach_schedules_booked'];

    public function user_module_progresses()
    {
        return $this->belongsTo(UserModuleProgress::class, 'object_id');
    }
    public function coach_schedules_booked()
    {
        return $this->belongsTo(CoachSceduleBooked::class, 'object_id', 'coach_schedules_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
     public function creditpackage()
    {
        return $this->hasOne(CreditPackage::class,'credit', 'credit_score');
    }
    public function coach_booked_schedule()
    {
        return $this->belongsTo(CoachSceduleBooked::class, 'object_id', 'id')->withoutGlobalScopes();
    }
}
