<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserModulesExercisesQuestion extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_modules_exercises_questions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'program_id', 'module_id', 'module_exercise_id', 'question_id', 'answer', 'coach_respond', 'coach_respond_at','is_gratuate_answer','popup_option'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('user_modules_exercises_questions.deleted', function (Builder $builder) {
            $builder->where('user_modules_exercises_questions.deleted', '=', '0');
        });
    }
}
