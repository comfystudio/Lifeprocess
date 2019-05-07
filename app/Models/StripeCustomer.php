<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeCustomer extends Model
{
 	protected $table = "stripe_customers";

 	protected $fillable = [
 			"client_id","stripe_customer_id","object","account_balance","currency",
 			"default_source","description","discount","email","livemode",
 			];		
}