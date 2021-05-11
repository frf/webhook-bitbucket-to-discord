<?php


namespace App\Repositories;

use App\Exceptions\ResourceNotFoundException;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($columns = ['*'])
    {
        return $this->model->paginate(15);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $record = $this->show($id);

        if (!$record instanceof $this->model) {
            throw new ResourceNotFoundException();
        }

        $this->model->where('id', $id)->update($data);

        return $this->show($id);
    }

    public function delete($id): int
    {
        return $this->model->destroy($id);
    }

    public function show($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function with($relations)
    {
        return $this->model->with($relations);
    }

    public function search(Builder $query = null)
    {
        return $this->getModel()->newEloquentBuilder($query)->get();
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        // TODO: Implement paginate() method.
    }

    public function find($id, $columns = array('*'))
    {
        // TODO: Implement find() method.
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        // TODO: Implement findBy() method.
    }

    public function deleteWhere(array $data)
    {
        // TODO: Implement deleteWhere() method.
    }

    public function findWhere(array $where, $columns = ['*'])
    {
        // TODO: Implement findWhere() method.
    }
}
