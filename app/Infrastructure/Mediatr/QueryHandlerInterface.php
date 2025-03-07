<?php
namespace App\Infrastructure\Mediatr;


use App\Infrastructure\Mediatr\QueryInterface;

interface QueryHandlerInterface {
    /**
     * Traite la query et retourne la réponse.
     *
     * @param QueryInterface $query
     * @return mixed
     */
    public function handle(QueryInterface $query);
}