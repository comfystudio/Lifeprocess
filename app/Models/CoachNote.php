<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CoachNote extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coach_notes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'user_id', 'note'];

    protected $dependency = array(        
    );

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('coach_notes.deleted', function(Builder $builder) {
            $builder->where('coach_notes.deleted', '=', '0');
        });
    }

    // Many to one relationhip with Coach
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'user_id');
    }

    // Many to one relationhip with Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
