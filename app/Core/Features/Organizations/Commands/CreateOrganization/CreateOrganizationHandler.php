<?php       
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Domain\Models\Organization;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;

class CreateOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $OrganizationRepository;

    public function __construct(IOrganizationRepository $OrganizationRepository)
    {
        $this->OrganizationRepository = $OrganizationRepository;
    }

    /**
     * @param CreateOrganizationCommand $command
     * @return object
     */
    public function handle(CommandInterface $command) : object
    {
        if (!$command instanceof CreateOrganizationCommand) {
            throw new \InvalidArgumentException("La commande doit être une instance de CreateOrganizationCommand");
        }

        $validatedData = (new CreateOrganizationValidator())->validate(data: [
            'nom' => $command->nom ?? null,
            'contact' => $command->contact ?? null,
            'adresse_complete' => $command->adresse_complete ?? null,
        ]);

        $organization = new Organization($validatedData);
        $Organizatione = $this->OrganizationRepository->add(entity: $organization);

        return $Organizatione;
    }
}
