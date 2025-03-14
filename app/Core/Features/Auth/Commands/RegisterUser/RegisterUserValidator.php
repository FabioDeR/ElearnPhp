<?php
namespace App\Core\Features\Auth\Commands\RegisterUser;


use App\Core\Exceptions\ValidatorInterface;
use App\Domain\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterUserValidator implements ValidatorInterface
{
    
    public function validate(array $data): array
    {
        // Création du validateur sans exécuter validate() immédiatement
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérification des erreurs avant de valider
        if ($validator->fails()) {
            throw new \InvalidArgumentException(json_encode($validator->errors()->all()));
        }
        // Retourner les données validées
    return $validator->validated();
    }

    public function hydrate(array $data): User
    {
        $validateUser = $this->validate($data);
        $newUser = new User($validateUser);   
        return $newUser;
    }
}

