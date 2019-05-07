<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class OtherCoachFeedbackList extends MyModel {

	protected $table = 'other_coach_feedback_lists';

	protected $fillable = ['coach_id', 'proxy_coach_id'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('other_coach_feedback_lists.deleted', function (Builder $builder) {
            $builder->where('other_coach_feedback_lists.deleted', '=', '0');
        });
    }
    public function clients() {
        return $this->hasMany(Client::class, 'coach_id','coach_id');
    }
    public function coach() {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
}
