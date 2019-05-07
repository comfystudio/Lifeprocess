<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CompletedCoachingSession extends MyModel
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'completed_coaching_sessions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['booked_schedule_id', 'contact_methods', 'contact_detail', 'remarks', 'completed_at'];
    public $timestamps = false;

    protected static function boot() {
        parent::boot();
    }
    public function coachscedulebooked() 
    {
        return $this->belongsTo(CoachSceduleBooked::class,'id');
    }
    
}
