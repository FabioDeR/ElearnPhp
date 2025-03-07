<?php
namespace App\Infrastructure\Mediatr;


use App\Infrastructure\Mediatr\CommandInterface;
use App\Infrastructure\Mediatr\MediatorInterface;
use Exception;

class SimpleMediator implements MediatorInterface {
    /**
     * @var array<string, CommandHandlerInterface>
     */
    private array $commandHandlers = [];
    
    /**
     * @var array<string, QueryHandlerInterface>
     */
    private array $queryHandlers = [];

    // Méthode pour enregistrer un handler de commande
    public function registerCommandHandler(string $commandClass, CommandHandlerInterface $handler): void {
        $this->commandHandlers[$commandClass] = $handler;
    }

    // Méthode pour envoyer une commande
    public function send(CommandInterface $command) {
        $commandClass = get_class($command);
        if (!isset($this->commandHandlers[$commandClass])) {
            throw new Exception("Aucun handler enregistré pour la commande $commandClass");
        }
        return $this->commandHandlers[$commandClass]->handle($command);
    }
    
    // Nouvelle méthode pour les queries
    public function registerQueryHandler(string $queryClass, QueryHandlerInterface $handler): void {
        $this->queryHandlers[$queryClass] = $handler;
    }

    public function ask(QueryInterface $query) {
        $queryClass = get_class($query);
        if (!isset($this->queryHandlers[$queryClass])) {
            throw new Exception("Aucun handler enregistré pour la query $queryClass");
        }
        return $this->queryHandlers[$queryClass]->handle($query);
    }
}
