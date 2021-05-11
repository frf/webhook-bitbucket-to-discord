<?php namespace App\Repositories;

use App\Repositories\Criterias\Criteria;
use App\Repositories\Criterias\CriteriaInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * Class Repository
 * @package Bosnadev\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface, CriteriaInterface
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    public $perPage;

    /**
     * @param  App  $app
     * @param  Collection  $collection
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->perPage = request()->get('per_page') ?: 10;
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @param  array  $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->select($columns)->paginate($this->perPage);
    }

    /**
     * @param  int  $perPage
     * @param  array  $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->makeModel()->create($data);
    }

    /**
     * @param  array  $data
     * @param $id
     * @param  string  $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->makeModel()->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->makeModel()->where('id', $id)->delete();
    }

    /**
     * @param array $where
     * @return int
     */
    public function deleteWhere(array $where)
    {
        $this->applyConditions($where);

        $deleted = $this->model->delete();

        $this->makeModel();

        return $deleted;
    }

    /**
     * @param $id
     * @param  array  $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param  array  $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyCriteria();

        $this->applyConditions($where);

        return $this->model->select($columns)->paginate($this->perPage);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = app()->make($this->model());

        if (!$model instanceof Model) {
            throw new RuntimeException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model->newQuery();
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * @param  bool  $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param  Criteria  $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * @param  Criteria  $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param  mixed  $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }
}
