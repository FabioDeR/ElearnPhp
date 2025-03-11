<?php
namespace App\Infrastructure\Persistence\Repositories;

use App\Core\Contracts\Infrastucture\IAsyncRepository;
use Illuminate\Database\Eloquent\Model;

class AsyncRepository implements IAsyncRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getById(string $id)
    {
        return $this->model->find($id);
    }

    public function listAll()
    {
        return $this->model->all();
    }

    public function add($entity)
    {
        $entity->save();
        return $entity;
    }

    public function update($entity)
    {
       return $entity->update();      

    }

    public function delete($entity)
    {
        return $entity->delete();
    }
}
