<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CoachModuleRate extends MyModel
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coach_module_rates';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['coach_id', 'program_id', 'module_id', 'rate'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('coach_module_rates.deleted', function (Builder $builder) {
            $builder->where('coach_module_rates.deleted', '=', '0');
        });
    }
    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
