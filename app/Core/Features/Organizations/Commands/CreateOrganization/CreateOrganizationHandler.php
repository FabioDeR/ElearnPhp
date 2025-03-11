<?php       
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;

class CreateOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $OrganizationRepository;
    private CreateOrganizationValidator $validator;

    public function __construct(IOrganizationRepository $OrganizationRepository, CreateOrganizationValidator $validator)
    {
        $this->OrganizationRepository = $OrganizationRepository;
        $this->validator = $validator;
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
        $newOrganization =  $this->validator->hydrate([
            'name' => $command->name ?? null,
            'contact' => $command->contact ?? null,
            'full_address' => $command->full_address ?? null,
        ]);

        return $this->OrganizationRepository->add($newOrganization);
    }
}
