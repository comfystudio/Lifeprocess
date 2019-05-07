<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ModulesExercisesQuestion extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modules_exercises_questions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'module_exercise_id', 'parent_question_id', 'question_title', 'question_no', 'helpblock', 'answer_format', 'min_value', 'max_value','min_range_value','max_range_value'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('modules_exercises_questions.deleted', function(Builder $builder) {
            $builder->where('modules_exercises_questions.deleted', '=', '0');
        });
    }

    /**
     * Set the parent_question_id value to 0 if it is blank
     *
     * @param  array  $value
     * @return void
     */
    public function setParentQuestionIdAttribute($value)
    {
        $this->attributes['parent_question_id'] = ($value != '') ? $value : 0 ;
    }
    /**
     * Set the min_value value to 0 if it is blank
     *
     * @param  array  $value
     * @return void
     */
    public function setMinValueAttribute($value)
    {
        $this->attributes['min_value'] = ($value != '') ? $value : 0 ;
    }
    /**
     * Set the max_value value to 0 if it is blank
     *
     * @param  array  $value
     * @return void
     */
    public function setMaxValueAttribute($value)
    {
        $this->attributes['max_value'] = ($value != '') ? $value : 0 ;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function coach() {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function module_exercise() {
        return $this->belongsTo(ModuleExercise::class, 'module_exercise_id');
    }
    public function module_exercise_question_options() {
        return $this->hasMany(ModulesExercisesQuestionsOption::class, 'question_id');
    }

    public function parent_question()
    {
        return $this->belongsTo(ModulesExercisesQuestion::class , 'parent_question_id');
    }

    public function sub_questions()
    {
        return $this->hasMany(ModulesExercisesQuestion::class, 'parent_question_id');
    }

    public function question_answer()
    {
        return $this->hasOne(UserModulesExercisesQuestion::class, 'question_id');
    }
}
