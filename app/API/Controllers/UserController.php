<?php

namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\Core\Features\Users\Commands\CreateUser\CreateUserCommand;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\API\Attributes\HttpPost;

class UserController extends Controller
{
    private MediatorInterface $mediator;

    public function __construct(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    #[HttpPost('/users/create')]
    public function create(#[FromBody] CreateUserCommand $command): JsonResponse
    {
        try {           
            $user = $this->mediator->send($command);
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    
}
