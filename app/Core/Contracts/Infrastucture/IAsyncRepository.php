<?php
namespace App\Core\Contracts\Infrastucture;

interface IAsyncRepository
{
    public function getById(string $id);
    public function listAll();
    public function add($entity);
    public function update($entity);
    public function delete($entity);
}