<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripePlan extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stripe_plans';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['program_id', 'name', 'plan_id', 'interval', 'currency', 'amount'];
}
