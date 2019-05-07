<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ModulesExercisesQuestionsOption extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modules_exercises_questions_options';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['question_id', 'option_value'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('modules_exercises_questions_options.deleted', function(Builder $builder) {
            $builder->where('modules_exercises_questions_options.deleted', '=', '0');
        });
    }

    public function module_exercise_question() {
        return $this->belongsTo(ModulesExercisesQuestion::class, 'question_id');
    }
}
