<?php
namespace App\Infrastructure\Mediatr;


use App\Infrastructure\Mediatr\CommandInterface;
use App\Infrastructure\Mediatr\MediatorInterface;
use Exception;

class SimpleMediator implements MediatorInterface {
    private array $handlers = [];

    public function registerHandler(string $commandClass, CommandHandlerInterface $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function send(CommandInterface $command)
    {
        $commandClass = get_class($command);
        if (!isset($this->handlers[$commandClass])) {
            throw new Exception("Aucun handler enregistré pour la commande $commandClass");
        }
        return $this->handlers[$commandClass]->handle($command);
    }
}
