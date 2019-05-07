<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserProgram extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_programs';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'program_id', 'start_datetime','is_intro_video_watch','is_gratuate_video_watch'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('user_programs.deleted', function (Builder $builder) {
            $builder->where('user_programs.deleted', '=', '0');
        });
    }

    public function program() {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
