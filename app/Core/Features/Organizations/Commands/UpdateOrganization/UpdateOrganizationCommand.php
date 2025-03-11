<?php

namespace App\Core\Features\Organizations\Commands\UpdateOrganization;

use App\Infrastructure\Mediatr\CommandInterface;

class UpdateOrganizationCommand implements CommandInterface
{
    public string $id;
    public string $name;
    public string $contact;
    public string $full_address; // Déclarez bien la propriété

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->name       = $data['name'] ?? '';
        $this->contact    = $data['contact'] ?? '';
        $this->full_address = $data['full_address'] ?? '';
    }
}