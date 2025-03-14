<?php

namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\API\Attributes\HttpDelete;
use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\API\Attributes\HttpPut;
use App\API\Exceptions\ApiResponse;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Routing\Controller;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
use App\Core\Features\Organizations\Commands\UpdateOrganization\UpdateOrganizationCommand;
use App\Core\Features\Organizations\Commands\DeleteOrganization\DeleteOrganizationCommand;
use App\Core\Features\Organizations\Queries\GetAllOrganization\GetAllOrganizationQuery;
use App\Core\Features\Organizations\Queries\GetOrganizationById\GetByIdOrganizationQuery;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * 
@OA\Info(
    *      title="Gamelle API",
    *      version="1.0.0",
    *      description="Documentation de l'API PtitGamelle")
    * 
    * @OA\Tag(
    *     name="Organizations",
    *     description="Operations sur les organisations"
    * )
    */
class OrganizationController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    #[HttpGet('/{id}')]
    public function getById(string $id) : JsonResponse 
        {
        try {
            $result = $this->mediator->ask(new GetByIdOrganizationQuery($id));

            if (!$result) {
                return ApiResponse::error("Organisation non trouvée.", 404);
            }

            return ApiResponse::success($result);
        } catch (\Throwable $e) {
            return ApiResponse::error("Erreur interne", 500, $e->getMessage());
        }
    }  

    #[HttpGet('/')]
    public function getAll(): JsonResponse
    {        
        try {
            $result = $this->mediator->ask(new GetAllOrganizationQuery());
            return ApiResponse::success($result);
        } catch (\Throwable $e) {
            return ApiResponse::error("Erreur interne", 500, $e->getMessage());
        }
    }

    #[HttpPost('/create')]
    public function create(#[FromBody] CreateOrganizationCommand $command): JsonResponse
    {
        try {
            $result = $this->mediator->send($command);
            return ApiResponse::success($result, "Organisation créée avec succès.", 201);
        } catch (\Throwable $e) {
            return ApiResponse::error("Impossible de créer l'organisation", 500, $e->getMessage());
        }
    }

    #[HttpPut('/update')]
    public function update(#[FromBody] UpdateOrganizationCommand $command): JsonResponse
    {
        try {
            $result = $this->mediator->send($command);
            return ApiResponse::success($result, "Organisation mise à jour avec succès.", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error("Erreur lors de la mise à jour.", 500, $e->getMessage());
        }
    }

    #[HttpDelete('/delete/{id}')]
    public function delete(string $id): JsonResponse
    {
        try {
            $this->mediator->send(new DeleteOrganizationCommand($id));
            return ApiResponse::success([], "Organisation supprimée avec succès.", 200);
        } catch (\Throwable $e) {
            return ApiResponse::error("Erreur lors de la suppression.", 500, $e->getMessage());
        }
    }
}