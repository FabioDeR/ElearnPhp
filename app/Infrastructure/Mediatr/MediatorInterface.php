<?php       
namespace App\Infrastructure\Mediatr;


interface MediatorInterface {
    public function send(CommandInterface $command);
    public function ask(QueryInterface $query);
}


