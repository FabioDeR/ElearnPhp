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
use L5Swagger\Annotations as SWG;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Boite à Tartine API",
 *         description="Documentation de l'API"
 *     )
 * )
 */

class OrganizationController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    /**
     * @SWG\Get(
     *     path="/organization/{id}",
     *     summary="Obtenir une organisation par ID",
     *     tags={"Organizations"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="string",
     *         description="ID de l'organisation"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Organisation trouvée"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Organisation non trouvée"
     *     )
     * )
     */
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

 /**
     * @SWG\Post(
     *     path="/organization/create",
     *     summary="Créer une nouvelle organisation",
     *     tags={"Organizations"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(property="nom", type="string", example="Mon Entreprise"),
     *             @SWG\Property(property="contact", type="string", example="0123456789"),
     *             @SWG\Property(property="adresse_complete", type="string", example="123 Rue de Laravel")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Organisation créée avec succès"
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Données invalides"
     *     )
     * )
     */
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
