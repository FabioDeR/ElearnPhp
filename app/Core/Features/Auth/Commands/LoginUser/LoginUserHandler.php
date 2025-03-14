<?php
namespace App\Core\Features\Auth\Commands\LoginUser;

use App\Core\Contracts\Infrastucture\IUserRepository;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class LoginUserHandler implements CommandHandlerInterface
{
    private IUserRepository $UserRepository;
    private LoginUserValidator $validator;

    public function __construct(IUserRepository $UserRepository, LoginUserValidator $validator)
    {
        $this->UserRepository = $UserRepository;
        $this->validator = $validator;
    }   

    /**
     * @param LoginUserCommand $command
     * @return array
     */
    public function handle(CommandInterface $command) : array
    {
        if (!$command instanceof LoginUserCommand) {
            throw new \InvalidArgumentException("La commande doit être une instance de LoginUserCommand");
        }     
        $validatedData  =  $this->validator->validate([
            'email' => $command->email ?? null,
            'password' => $command->password ?? null,
        ]);

        $user = $this->UserRepository->findByEmail($validatedData['email']);

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        try {           
        
            $token = $user->createToken('auth_token')->plainTextToken;
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de la création du token",e($e->getMessage()));
        }
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}