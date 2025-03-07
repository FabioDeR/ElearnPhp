<?php       
namespace App\API\Controllers;

use App\API\Attributes\FromBody;
use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
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

    #[HttpGet('/')]
    public function index(){
        return response()->json(['message' => 'Hello World']);
    }

    #[HttpPost('/create')]
    public function create(Request $request,#[FromBody] CreateOrganizationCommand $command): JsonResponse
    {
        try {
            $response = $this->mediator->send($command);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
