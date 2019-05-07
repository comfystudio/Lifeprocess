<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CoachProgram extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coach_program';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['coach_id', 'program_id'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('coach_program.deleted', function(Builder $builder) {
            $builder->where('coach_program.deleted', '=', '0');
        });
    }
    public function coach_program_detail() {
        return $this->belongsTo(Program::class, 'program_id');
    }

}
