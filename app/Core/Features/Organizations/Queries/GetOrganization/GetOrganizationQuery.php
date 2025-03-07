<?php
namespace App\Core\Features\Organizations\Queries\GetOrganization;

use App\Infrastructure\Mediatr\QueryInterface;

class GetOrganizationQuery implements QueryInterface {
    public string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }
}