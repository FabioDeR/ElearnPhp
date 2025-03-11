<?php

namespace App\Core\Features\Organizations\Commands\DeleteOrganization;

use App\Infrastructure\Mediatr\CommandInterface;

class DeleteOrganizationCommand implements CommandInterface
{
    public string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }
}