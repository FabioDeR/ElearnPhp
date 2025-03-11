<?php

namespace App\Core\Features\Organizations\Commands\UpdateOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationValidator;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use GrahamCampbell\ResultType\Success;

class UpdateOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $repository;
    private CreateOrganizationValidator $validator;

    public function __construct(IOrganizationRepository $repository, CreateOrganizationValidator $validator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }
    
    /**
     * @param UpdateOrganizationCommand $command
     * @return mixed
     */
    public function handle(CommandInterface $command): mixed
    {        
        
        if (!$command instanceof UpdateOrganizationCommand) {
            throw new \InvalidArgumentException("La commande doit être une instance de UpdateOrganizationCommand");
        }
    
        // 1️⃣ Récupérer l'organisation existante
        $organization = $this->repository->getById($command->id);
        
        if (!$organization) {
            throw new \RuntimeException("Organisation non trouvée");
        }
    
        // 2️⃣ Appliquer les modifications si elles existent
        $validatedData = $this->validator->validate([
            'name' => $command->name ?? $organization->name,
            'contact' => $command->contact ?? $organization->contact,
            'full_address' => $command->full_address ?? $organization->full_address,
        ]);
    
        $organization->fill($validatedData);
    
        // 3️⃣ Sauvegarde et retour de l'objet mis à jour
        return [
            "success" => $this->repository->update($organization),
            "message" => "Organization updated successfully"
        ];
    }
}