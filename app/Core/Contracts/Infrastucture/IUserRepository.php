<?php
namespace App\Core\Contracts\Infrastucture;

use App\Domain\Models\User;

interface IUserRepository extends IAsyncRepository
{
    public function findByEmail(string $email): ?User;
}


