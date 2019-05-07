<?php
$stripe = array(
  "publishable_key" => env('STRIPE_KEY'),
  "secret_key"      => env('STRIPE_SECRET'),
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

