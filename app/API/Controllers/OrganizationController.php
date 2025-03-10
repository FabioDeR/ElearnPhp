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
use OpenApi\Annotations as OA;
use Throwable;

/**
 * @OA\Info(
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

     /**
     * @OA\Get(
     *     path="/api/organization/{id}",
     *     summary="Obtenir une organisation par son ID",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="L'identifiant unique de l'organisation",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organisation trouvée",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="name", type="string", example="Tech Corp"),
     *                 @OA\Property(property="contact", type="string", example="0123456789"),
     *                 @OA\Property(property="full_address", type="string", example="123 PHP Street")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organisation non trouvée"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne"
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
     * @OA\Post(
     *     path="/api/organization/create",
     *     summary="Créer une nouvelle organisation",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "contact", "full_address"},
     *             @OA\Property(property="name", type="string", example="Tech Corp"),
     *             @OA\Property(property="contact", type="string", example="0123456789"),
     *             @OA\Property(property="full_address", type="string", example="123 PHP Street")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Organisation créée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Organisation créée avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="name", type="string", example="Tech Corp"),
     *                 @OA\Property(property="contact", type="string", example="0123456789"),
     *                 @OA\Property(property="full_address", type="string", example="123 PHP Street")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête invalide"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne"
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
