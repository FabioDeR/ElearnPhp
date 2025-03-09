<?php

namespace App\Core\Features\Users\Commands\CreateUser;

use App\Infrastructure\Mediatr\CommandInterface;

class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $email,
        public string $organizationId,
        public string $password
    ) {}
}