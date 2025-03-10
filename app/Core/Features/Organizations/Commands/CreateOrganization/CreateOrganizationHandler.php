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

        if (!$command instanceof CreateOrganizationCommand) {
            throw new \InvalidArgumentException("The command must be an instance of CreateOrganizationCommand");
        }
    
        $validatedData = (new CreateOrganizationValidator())->validate([
            'name' => $command->name ?? null,
            'contact' => $command->contact ?? null,
            'full_address' => $command->full_address ?? null,
        ]); 
        
    
        // 🔥 Create the Organization and save it
        $organization = new Organization($validatedData);    

        return $this->OrganizationRepository->add(entity: $organization);;
    }
}
