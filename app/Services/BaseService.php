<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseService implements ServiceInterface
{

    private $repo;

    public function __construct(BaseRepository $repository)
    {
        $this->repo = $repository;
    }

    public function all(): ?LengthAwarePaginator
    {
        return $this->repo->all();
    }

    public function create(array $data): Model
    {
        return $this->repo->create($data);
    }

    public function update(array $data, $id): Model
    {
        return $this->repo->update($data, $id);
    }

    public function delete($id)
    {
        $model = $this->repo->show($id);

        if (!$model instanceof Model) {
            throw new ResourceNotFoundException();
        }

        try {
            $model->delete();
        } catch (\Exception $e) {
            throwException($e);
        }

        return true;
    }

    public function show($id): Model
    {
        $model = $this->repo->show($id);

        if (!$model instanceof Model) {
            throw new ResourceNotFoundException();
        }

        return $model;
    }
}
