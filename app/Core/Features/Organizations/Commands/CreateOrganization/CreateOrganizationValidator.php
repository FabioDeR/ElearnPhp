<?php
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Exceptions\ValidatorInterface;
use Illuminate\Support\Facades\Validator;

class CreateOrganizationValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        return Validator::make($data, [
           'nom' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'adresse_complete' => 'required|string',
        ])->validate();

        if ($validator->fails()) {
            throw new \InvalidArgumentException(json_encode($validator->errors()->all()));
        }

        return $data;
    }
}