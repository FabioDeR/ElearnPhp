<?php       
namespace App\Infrastructure\Mediatr;


interface CommandHandlerInterface  {
    public function handle(CommandInterface $command) : mixed;
}