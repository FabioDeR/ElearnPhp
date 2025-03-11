<?php

namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\API\Attributes\HttpDelete;
use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\API\Attributes\HttpPut;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
use App\Core\Features\Organizations\Commands\UpdateOrganization\UpdateOrganizationCommand;
use App\Core\Features\Organizations\Commands\DeleteOrganization\DeleteOrganizationCommand;
use App\Core\Features\Organizations\Queries\GetAllOrganization\GetAllOrganizationQuery;
use App\Core\Features\Organizations\Queries\GetOrganizationById\GetByIdOrganizationQuery;

class OrganizationController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    #[HttpGet('/{id}')]
    public function getById(string $id)
    {
        return response()->json($this->mediator->ask(new GetByIdOrganizationQuery($id)), 200);
    }  
   

    #[HttpGet('/')]
    public function getAll()    
    {
        return response()->json($this->mediator->ask(new GetAllOrganizationQuery()), 200);
    }

    #[HttpPost('/create')]
    public function create(#[FromBody] CreateOrganizationCommand $command)
    {
        return response()->json($this->mediator->send($command), 201);
    }

    #[HttpPut('/update')]
    public function update(#[FromBody] UpdateOrganizationCommand $command)
    {
        return response()->json($this->mediator->send($command), 200);
    }

    #[HttpDelete('/delete/{id}')]
    public function delete(string $id)
    {
        return response()->json($this->mediator->send(new DeleteOrganizationCommand($id)), 200);
    }
}