<?php
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use Illuminate\Support\Facades\Validator;

class CreateOrganizationValidator
{
    public static function validate(array $data)
    {
        return Validator::make($data, [
            'nom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'adresse_complete' => 'nullable|string|max:255'
        ]);
    }
}