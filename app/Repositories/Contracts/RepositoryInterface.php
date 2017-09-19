<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    // public function where($conditions, $operator = null, $value = null);

    // public function orWhere($conditions, $operator = null, $value = null);

    // public function count();
    
    // public function get($columns = ['*']);

    // public function lists($column, $key = null);

    /**
     * Retrieve all data of repository
     */
    public function all($columns = ['*']);

     /**
     * Find data by id
     */
    public function find($id, $columns = ['*']);

    public function paginate($limit = null, $columns = ['*']);
    /**
     * Save a new entity in repository
     */
    public function create(array $input);

    public function update(array $input, $id);
    
    public function delete($id);
}
