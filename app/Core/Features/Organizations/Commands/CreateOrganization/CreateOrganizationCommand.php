<?php
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Infrastructure\Mediatr\CommandInterface;




class CreateOrganizationCommand implements CommandInterface
{
    
    public string $nom;
    public string $contact;
    public string $adresse_complete; // Déclarez bien la propriété

    public function __construct(array $data)
    {
        $this->nom            = $data['nom'] ?? '';
        $this->contact        = $data['contact'] ?? '';
        $this->adresse_complete = $data['adresse_complete'] ?? '';
    }
}
