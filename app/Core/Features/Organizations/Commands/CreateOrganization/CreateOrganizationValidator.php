<?php
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Exceptions\ValidatorInterface;
use App\Domain\Models\Organization;
use Illuminate\Support\Facades\Validator;

class CreateOrganizationValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        // Création du validateur sans exécuter validate() immédiatement
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'full_address' => 'required|string',
        ]);

        // Vérification des erreurs avant de valider
        if ($validator->fails()) {
            throw new \InvalidArgumentException(json_encode($validator->errors()->all()));
        }
        // Retourner les données validées
        return $validator->validated();
    }

    public function hydrate(array $data): Organization
    {
        $validateOrganization = $this->validate($data);
        $organization = new Organization($validateOrganization);   
        return $organization;
    }
}