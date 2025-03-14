<?php
namespace App\Core\Features\Auth\Commands\LoginUser;

use App\Infrastructure\Mediatr\CommandInterface;

class LoginUserCommand implements CommandInterface
{
    public string $email;
    public string $password;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
    }   
}