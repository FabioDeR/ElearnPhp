<?php       
namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\API\Exceptions\ApiResponse;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
use App\Core\Features\Organizations\Queries\GetOrganization\GetOrganizationQuery;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;


class OrganizationController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    #[HttpGet('/{id}')]
public function GetbyId(string $id)
{
    try {
        $query = new GetOrganizationQuery($id);
        $organization = $this->mediator->ask($query);

        if (!$organization) {
            return ApiResponse::error("Organisation non trouvée", 404);
        }

        return ApiResponse::success($organization);
    } catch (\Exception $e) {
        return ApiResponse::error("Une erreur interne s'est produite", 500, $e->getMessage());
    }
}

#[HttpPost('/create')]
public function create(Request $request, #[FromBody] CreateOrganizationCommand $command): JsonResponse
{
    try {
        $response = $this->mediator->send($command);
        return ApiResponse::success($response, "Organisation créée avec succès", 201);
    } catch (Throwable $e) {
        return ApiResponse::error("Impossible de créer l'organisation", 500, $e->getMessage());
    }
}
}
