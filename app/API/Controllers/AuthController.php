<?php
namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\API\Attributes\HttpPost;
use App\API\Exceptions\ApiResponse;
use App\Core\Features\Auth\Commands\LoginUser\LoginUserCommand;
use App\Core\Features\Auth\Commands\RegisterUser\RegisterUserCommand;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *      title="Elearn API",
 *      version="1.0.0",
 *      description="Documentation de l'API Elearn"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Operations liées à l'authentification des utilisateurs"
 * )
 */
class AuthController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

     /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Créer un compte utilisateur",
     *     description="Enregistre un nouvel utilisateur et retourne ses informations",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Password123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Utilisateur enregistré avec succès."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Les données fournies sont invalides"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    #[HttpPost('/register')]
    public function register(#[FromBody] RegisterUserCommand $command): JsonResponse
    {        
        $user = $this->mediator->send($command);
        return ApiResponse::success($user, "Utilisateur enregistré avec succès.", 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="Connexion de l'utilisateur",
     *     description="Authentifie un utilisateur et retourne un token d'accès",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="johndoe@example.com")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|dju94jdkl43920jf94jdf094jf")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Échec de l'authentification",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Identifiants invalides.")
     *         )
     *     )
     * )
     */
    #[HttpPost('/login')]
    public function login(#[FromBody] LoginUserCommand $command): JsonResponse
    {
        $response = $this->mediator->send($command);
        return ApiResponse::success($response, "Connexion réussie.");
    }

}