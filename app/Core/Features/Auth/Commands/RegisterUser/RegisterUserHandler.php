<?php
namespace App\Core\Features\Auth\Commands\RegisterUser;

use App\Core\Contracts\Infrastucture\IUserRepository;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Domain\Models\User;
use App\Infrastructure\Mediatr\CommandInterface;
use Illuminate\Console\Command;

class RegisterUserHandler implements CommandHandlerInterface
{
    private IUserRepository $repository;
    private RegisterUserValidator $validator;

    public function __construct(IUserRepository $repository, RegisterUserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }
     /**
     * @param RegisterUserCommand $command
     * @return object
     */
    public function handle(CommandInterface $command) : object
    {
        if (!$command instanceof RegisterUserCommand) {
            throw new \InvalidArgumentException("La commande doit être une instance de CreateOrganizationCommand");
        }     
        $newUser =  $this->validator->hydrate([
            'name' =>  $command->name ?? null,
            'email' =>  $command->email ?? null,
            'password'=> $command->password ?? null,
            'password_confirmation'=> $command->password_confirmation ?? null,            
        ]);

        return $this->repository->add($newUser);
    }
}
