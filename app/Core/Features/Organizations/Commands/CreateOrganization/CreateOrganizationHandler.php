<?php       
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;

class CreateOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $OrganizationRepository;

    public function __construct(IOrganizationRepository $OrganizationRepository)
    {
        $this->OrganizationRepository = $OrganizationRepository;
    }

    public function handle(CommandInterface $command): mixed
    {
         // Validation des données AVANT d'exécuter la commande
        $validatedData = (new CreateOrganizationValidator())->validate(data: [
            'nom' => $command->nom ?? null,
            'contact' => $command->contact ?? null,
            'adresse_complete' => $command->adresse_complete ?? null,
        ]);

        $Organizatione = $this->OrganizationRepository->add(entity: $validatedData);        

        return $Organizatione;
    }
}
