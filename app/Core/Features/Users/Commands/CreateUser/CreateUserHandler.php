<?php

namespace App\Core\Features\Users\Commands\CreateUser;

use App\Domain\Models\User;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class CreateUserHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): mixed
    {
        // Vérifie que l'objet reçu est bien une instance de CreateUserCommand
        if (!$command instanceof CreateUserCommand) {
            throw new InvalidArgumentException("Invalid command type. Expected CreateUserCommand.");
        }

        // Validation des données
        CreateUserValidator::validate([
            'name' => $command->name,
            'email' => $command->email,
            'organizationId' => $command->organizationId,
            'password' => $command->password
        ]);

        // Création de l'utilisateur avec hashage du mot de passe
        return User::create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => Hash::make($command->password), // Sécurisation du mot de passe
            'organization_id' => $command->organizationId,
            'must_reset_password' => true
        ]);
    }
}