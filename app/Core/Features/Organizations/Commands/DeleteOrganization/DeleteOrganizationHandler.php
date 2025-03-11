<?php

namespace App\Core\Features\Organizations\Commands\DeleteOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use GuzzleHttp\Psr7\Message;

use function PHPSTORM_META\map;

class DeleteOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $repository;

    public function __construct(IOrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    
    /**
     * @param DeleteOrganizationCommand $command
     * @return mixed
     */
    public function handle(CommandInterface $command): mixed
    {
        $organization = $this->repository->getById($command->id);
        if ($organization === null) {
            return [
                "success" => false,
                "message" => "Organization not found"
            ];
        }        
        return [
            "success" => $this->repository->delete($organization),
            "message" => "Organization deleted successfully"
        ];
    }
    
}