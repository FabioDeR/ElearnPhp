<?php
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Infrastructure\Mediatr\CommandInterface;




class CreateOrganizationCommand implements CommandInterface
{    
    public string $name;
    public string $contact;
    public string $full_address; // Déclarez bien la propriété

    public function __construct(array $data)
    {
        $this->name       = $data['name'] ?? '';
        $this->contact    = $data['contact'] ?? '';
        $this->full_address = $data['full_address'] ?? '';
    }
}
