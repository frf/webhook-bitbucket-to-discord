<?php


namespace App\Repositories\Criterias;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Criteria
{

    /**
     * @param $model
     * @param  RepositoryInterface  $repository
     * @return mixed
     */
    abstract public function apply(Model $model, RepositoryInterface $repository);
}
