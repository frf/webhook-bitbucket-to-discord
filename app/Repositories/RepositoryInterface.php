<?php


namespace App\Repositories;

interface RepositoryInterface
{

    public function all($columns = array('*'));

    public function paginate($perPage = 15, $columns = array('*'));

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function deleteWhere(array $data);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));

    public function findWhere(array $where, $columns = ['*']);
}
