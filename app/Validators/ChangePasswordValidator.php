<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App;
use Auth;
use Hash;

class ChangePasswordValidator extends Validator {
    public function validateCurrentPassword($attribute, $value, $parameters, $validator)
    {
        $user = Auth::user();
        if (auth()->validate([
            'email'    => $user->email,
            'password' => $value
        ])) {
            return true;
        } else {
            return false;
        }        
    }
}

