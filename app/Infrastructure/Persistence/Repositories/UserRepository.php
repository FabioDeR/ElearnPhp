<?php
namespace App\Infrastructure\Persistence\Repositories;

use App\Core\Contracts\Infrastucture\IUserRepository;
use App\Domain\Models\User;

class UserRepository extends AsyncRepository implements IUserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
    
}