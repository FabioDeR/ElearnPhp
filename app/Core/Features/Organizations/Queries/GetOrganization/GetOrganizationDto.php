<?php

namespace App\Core\Features\Organizations\Queries\GetOrganization;

class GetOrganizationDto
{
    public string $id;
    public string $nom;
    public string $contact;
    public string $adresse_complete;

    public function __construct(string $id, string $nom, string $contact, string $adresse_complete)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->contact = $contact;
        $this->adresse_complete = $adresse_complete;
    }
}