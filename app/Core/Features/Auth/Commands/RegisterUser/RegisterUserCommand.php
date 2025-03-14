<?php
namespace App\Core\Features\Auth\Commands\RegisterUser;

use App\Infrastructure\Mediatr\CommandInterface;

class RegisterUserCommand implements CommandInterface
{
    public string $name;
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->password_confirmation = $data['password_confirmation'] ?? '';
    }
}