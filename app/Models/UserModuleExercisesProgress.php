<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserModuleExercisesProgress extends MyModel
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_module_exercises_progresses';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'program_id', 'module_id', 'module_exercise_id', 'completed_at','is_gratuate_excersize'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('user_module_exercises_progresses.deleted', function (Builder $builder) {
            $builder->where('user_module_exercises_progresses.deleted', '=', '0');
        });
    }
    public function user_module_exercise_questions() {
        return $this->hasMany(ModulesExercisesQuestion::class, 'module_exercise_id', 'module_exercise_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
    public function module_exercise()
    {
        return $this->belongsTo(ModuleExercise::class, 'module_exercise_id');
    }
}
