<?php
namespace App\Core\Features\Auth\Commands\LoginUser;

use App\Core\Exceptions\ValidatorInterface;
use Illuminate\Support\Facades\Validator;

class LoginUserValidator implements ValidatorInterface
{
    
    public function validate(array $data): array
    {
        // Création du validateur sans exécuter validate() immédiatement
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Vérification des erreurs avant de valider
        if ($validator->fails()) {
            throw new \InvalidArgumentException(json_encode($validator->errors()->all()));
        }
        // Retourner les données validées
        return $validator->validated();
    }
    
}
