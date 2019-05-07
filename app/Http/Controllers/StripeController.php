<?php

namespace App\Http\Controllers;

use \Stripe\Plan;
use \Stripe\Token;
use \Stripe\Coupon;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    /**
     * Get the token id from the stripe token object
     * @param  array  $inputs [card details]
     * @return [string]         [stripe token id]
     */
    public function createStripeToken($inputs = [])
    {
        $stripeToken = Token::create([
            "card" => [
                "name" => $inputs['name'],
                "number"    => $inputs['number'],
                "exp_month" => $inputs['exp_month'],
                "exp_year"  => $inputs['exp_year'],
                "cvc"       => $inputs['cvc'],
            ],
        ]);
        //print_r($stripeToken); exit();
        return $stripeToken->id;
    }

    /**
     * For get the stripe token
     * @param  string $user [description]
     * @return [type]       [description]
     */
    public function retriveStripeToken($user = '')
    {
        $test = $user->asStripeCustomer();
        //dump($test);exit();
        $card_id = $test->sources->data[0]->id;

        $token_result = Token::retrieve($card_id);
        //dump($token_result);exit();
    }

    /**
     * Create new plan with the stripe
     * @param  [type] $params [description]
     * @param  [type] $opts   [description]
     * @return [type]         [description]
     */
    public function createPlan($params = null, $opts = null)
    {
        if (!empty($params)) {
            return Plan::create($params, $opts);
        }
        return false;
    }

    /**
     * Retrieve Plan from stipe
     * @param  [type] $plan_id [description]
     * @param  [type] $opts    [description]
     * @return [type]          [description]
     */
    public function retrivePlan($plan_id = null, $opts = null)
    {
        if (!empty($plan_id)) {
            return Plan::retrieve($plan_id, $opts);
        }
        return false;
    }

    /**
     * Check if the plan is exist then return plan details else create new plan and return the details
     * @param  [type] $params [description]
     * @param  [type] $opts   [description]
     * @return [type]         [description]
     */
    public function checkAndCreatePlan($params = null, $opts = null)
    {

        //print_r($params['stripe_program_name']);
        $exist_plans   = Plan::all();
        //print_r($exist_plans); exit;
        $is_exist_plan = false;
        if (!empty($exist_plans)) {
            $exist_plans = $exist_plans->data;
            foreach ($exist_plans as $exist_plan) {
                $existing_plan = collect($exist_plan);
                $existing_plan = $existing_plan->all();
                if ($existing_plan['id'] == $params['stripe_program_name']) {
                    $is_exist_plan = true;
                    return $existing_plan['id'];
                }
            }
        }

        // Make Plan data for create new plan
        $plan_data['id']       = str_slug($params['stripe_program_name']); //for id convert space with -
        $plan_data['amount']   = $params['program_fee']*100; // Stripe allows only amount in cent so convert dollar into cent. For es: $1 = 100 cent
        $plan_data['currency'] = 'cad'; // Three-letter ISO currency code, in lowercase. Must be a supported currency. for ex: cad,usd,gbp,etc.
        $plan_data['interval'] = 'day'; // Specifies billing frequency. Either day, week, month or year.
        $plan_data['name']     = $params['stripe_program_name']; // Name of the plan, to be displayed on invoices and in the web interface

        $new_plan = $this->createPlan($plan_data, $opts);

        return $new_plan->id;
    }
}
