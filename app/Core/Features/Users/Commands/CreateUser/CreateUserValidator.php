<?php

namespace App\Core\Features\Users\Commands\CreateUser;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateUserValidator
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'organizationId' => 'required|uuid', 
            'password' => 'nullable|string|min:8'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}