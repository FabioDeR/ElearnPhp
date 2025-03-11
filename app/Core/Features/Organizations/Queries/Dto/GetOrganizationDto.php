<?php

namespace App\Core\Features\Organizations\Queries\Dto;
class GetOrganizationDto
{
    public string $id;
    public string $name;
    public string $contact;
    public string $full_address;

    public function __construct(string $id, string $name, string $contact, string $full_address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->contact = $contact;
        $this->full_address = $full_address;
    }
}