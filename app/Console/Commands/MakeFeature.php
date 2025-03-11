<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeFeature extends Command
{
    protected $signature = 'make:feature {name}';
    protected $description = 'Génère un CRUD complet pour une entité donnée';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $paths = [
            "app/API/Controllers/{$name}Controller.php" => $this->getControllerStub($name),
            "app/Infrastructure/Persistence/Repositories/{$name}Repository.php" => $this->getRepositoryStub($name),

            // Commands (Create, Update, Delete)
            "app/Core/Features/{$name}s/Commands/Create{$name}/Create{$name}Command.php" => $this->getCommandStub($name, 'Create'),
            "app/Core/Features/{$name}/Commands/Create{$name}/Create{$name}Handler.php" => $this->getHandlerStub($name, 'Create'),

            "app/Core/Features/{$name}s/Commands/Update{$name}/Update{$name}Command.php" => $this->getCommandStub($name, 'Update'),
            "app/Core/Features/{$name}s/Commands/Update{$name}/Update{$name}Handler.php" => $this->getHandlerStub($name, 'Update'),

            "app/Core/Features/{$name}s/Commands/Delete{$name}/Delete{$name}Command.php" => $this->getCommandStub($name, 'Delete'),
            "app/Core/Features/{$name}s/Commands/Delete{$name}/Delete{$name}Handler.php" => $this->getHandlerStub($name, 'Delete'),

            // Queries (GetById, GetAll)
            "app/Core/Features/{$name}s/Queries/Get{$name}ById/Get{$name}Query.php" => $this->getQueryStub($name, 'ById'),
            "app/Core/Features/{$name}s/Queries/Get{$name}ById/Get{$name}Handler.php" => $this->getQueryHandlerStub($name, 'ById'),

            "app/Core/Features/{$name}s/Queries/GetAll{$name}/GetAll{$name}Query.php" => $this->getQueryStub($name, 'All'),
            "app/Core/Features/{$name}s/Queries/GetAll{$name}/GetAll{$name}Handler.php" => $this->getQueryHandlerStub($name, 'All'),

        ];

        foreach ($paths as $path => $stubContent) {
            if (!$this->files->exists($path)) {
                $this->files->ensureDirectoryExists(dirname($path));
                $this->files->put($path, $stubContent);
                $this->info("Créé : {$path}");
            } else {
                $this->warn("Existant : {$path}");
            }
        }

        $this->info("🚀 CRUD complet pour {$name} généré avec succès !");
    }

    protected function getControllerStub($name)
    {
        return "<?php

namespace App\API\Controllers;

use App\Core\Features\\{$name}s\\Commands\\Create{$name}\\Create{$name}Command;
use App\Core\Features\\{$name}s\\Commands\\Update{$name}\\Update{$name}Command;
use App\Core\Features\\{$name}s\\Commands\\Delete{$name}\\Delete{$name}Command;
use App\Core\Features\\{$name}s\\Queries\\Get{$name}ById\\Get{$name}Query;
use App\Core\Features\\{$name}s\\Queries\\GetAll{$name}\\GetAll{$name}Query;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\API\Attributes\FromBody;
use App\API\Attributes\HttpDelete;
use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\API\Attributes\HttpPut;
use App\Infrastructure\Mediatr\MediatorInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class {$name}Controller extends Controller
{
    private MediatorInterface \$mediator;

    public function __construct(MediatorInterface \$mediator)
    {
        \$this->mediator = \$mediator;
    }

    #[HttpGet('/{id}')]
    public function getById(string \$id)
    {
        return response()->json(\$this->mediator->ask(new Get{$name}Query(\$id)), 200);
    }

    #[HttpGet('/')]
    public function getAll()
    {
        return response()->json(\$this->mediator->ask(new GetAll{$name}Query()), 200);
    }

    #[HttpPost('/create')]
    public function create(Request \$request, #[FromBody] Create{$name}Command \$command)
    {
        return response()->json(\$this->mediator->send(\$command), 201);
    }

    #[HttpPut('/update/{id}')]
    public function update(string \$id, Request \$request, #[FromBody] Update{$name}Command \$command)
    {
        return response()->json(\$this->mediator->send(\$command), 200);
    }

    #[HttpDelete('/delete/{id}')]
    public function delete(string \$id)
    {
        return response()->json(\$this->mediator->send(new Delete{$name}Command(\$id)), 200);
    }
}";
    }

    protected function getCommandStub($name, $action)
    {
        return "<?php

namespace App\Core\Features\\{$name}s\\Commands\\{$action}{$name};

use App\Infrastructure\Mediatr\CommandInterface;

class {$action}{$name}Command implements CommandInterface
{
    public function __construct(
        public string \$id,
        public ?string \$name = null
    ) {}
}";
    }

    protected function getHandlerStub($name, $action)
    {
        return "<?php

namespace App\Core\Features\\{$name}s\\Commands\\{$action}{$name};

use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Persistence\Repositories\\{$name}Repository;

class {$action}{$name}Handler implements CommandHandlerInterface
{
    private {$name}Repository \$repository;

    public function __construct({$name}Repository \$repository)
    {
        \$this->repository = \$repository;
    }

    public function handle({$action}{$name}Command \$command)
    {
        if ('{$action}' === 'Create') {
            return \$this->repository->add(['name' => \$command->name]);
        } elseif ('{$action}' === 'Update') {
            return \$this->repository->update(\$command->id, ['name' => \$command->name]);
        } elseif ('{$action}' === 'Delete') {
            return \$this->repository->delete(\$command->id);
        }
    }
}";
    }

    protected function getQueryStub($name, $type)
    {
        return "<?php

namespace App\Core\Features\\{$name}s\\Queries\\Get{$type}{$name};

use App\Infrastructure\Mediatr\QueryInterface;

class Get{$type}{$name}Query implements QueryInterface
{
    public function __construct(
        public ?string \$id = null
    ) {}
}";
    }

    protected function getQueryHandlerStub($name, $type)
    {
        return "<?php

namespace App\Core\Features\\{$name}s\\Queries\\Get{$type}{$name};

use App\Infrastructure\Mediatr\QueryHandlerInterface;
use App\Infrastructure\Persistence\Repositories\\{$name}Repository;

class Get{$type}{$name}Handler implements QueryHandlerInterface
{
    private I{$name}Repository \$repository;

    public function __construct(I{$name}Repository \$repository)
    {
        \$this->repository = \$repository;
    }

    public function handle(Get{$type}{$name}Query \$query)
    {
        return \$query->id ? \$this->repository->getById(\$query->id) : \$this->repository->getAll();
    }
}";
    }
    protected function getRepositoryStub($name)
    {
        return "<?php

        namespace App\Infrastructure\Persistence\Repositories;

        use App\Domain\Models\\{$name};
        use App\Core\Contracts\Persistence\IAsyncRepository;S

        class {$name}Repository implements IAsyncRepository
        {
            public function __construct({$name} \$model)
            {
                \$this->model = \$model;
            }
        }";
    }
}
