<?php       
namespace App\Core\Features\Organizations\Commands\CreateOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Domain\Models\Organization;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use Illuminate\Support\Str;

class CreateOrganizationHandler implements CommandHandlerInterface
{
    private IOrganizationRepository $OrganizationRepository;

    public function __construct(IOrganizationRepository $OrganizationRepository)
    {
        $this->OrganizationRepository = $OrganizationRepository;
    }

    public function handle(CommandInterface $command)
    {
        $Organizatione = new Organization([
            'id' => Str::uuid(),
            'nom' => $command->nom,
            'contact' => $command->contact,
            'adresse_complete' => $command->adresse_complete
        ]);

        return $this->OrganizationRepository->add($Organizatione);
    }
}
