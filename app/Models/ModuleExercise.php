<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ModuleExercise extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'module_exercises';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'exercise_no', 'title', 'sort_description','reading_material'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('module_exercises.deleted', function(Builder $builder) {
            $builder->where('module_exercises.deleted', '=', '0');
        });
    }
    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function module_exercise_questions() {
        return $this->hasMany(ModulesExercisesQuestion::class, 'module_exercise_id');
    }
}
