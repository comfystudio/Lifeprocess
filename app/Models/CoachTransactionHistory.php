<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;


class CoachTransactionHistory extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coach_transaction_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'object_id', 'object_type', 'paypal_token', 'paypal_payerId', 'transaction_type', 'transaction_amount', 'transaction_detail', 'paypal_profile_id', 'paypal_profile_status', 'transaction_status', 'transaction_response', 'next_billing_date', 'last_payment_date', 'module_progress_id','format'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('coach_transaction_histories.deleted', function (Builder $builder) {
            $builder->where('coach_transaction_histories.deleted', '=', '0');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function object_coach()
    {
        return $this->belongsTo(CoachSceduleBooked::class, 'object_id')->with('user');
    }
    public function module_progress(){
        return $this->belongsTo(UserModuleProgress::class,'object_id','id')->with(['submittedBy','modules']);
    }
}
