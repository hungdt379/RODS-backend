<?php

namespace App\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
//use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class BaseRepository
 *
 * @package App\Domain\Repositories
 */
class BaseRepository
{
    /**
     * Get all records's the entity
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->query()->get();
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int  $perPage
     * @param  array  $columns
     *
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10, $columns = ['*'])
    {
        return $this->query()->paginate($perPage, $columns);
    }

    /**
     * Update information's the entity
     *
     * @param array $dataUpdate
     *
     * @return mixed
     */
    public function update(array $dataUpdate)
    {
        return $this->query()->update($dataUpdate);
    }

    /**
     *  Update with condition of entity
     *
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function updateWhere(array $data, $id, $attribute="id") {
        return $this->query()->where($attribute, '=', $id)->update($data);
    }

    /**
     * Create a new entity
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->query()->create($data);
    }

    /**
     * Count number of records entity
     *
     * @return mixed
     */
    public function getCount()
    {
        return $this->query()->count();
    }

    /**
     * Find the entity by id
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->query()->find($id);
    }

    /**
     * @param $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id)
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Find entity by $attribute
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->query()->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Get all results by multiple fields
     *
     * @param  array  $where
     * @param  array   $columns
     * @param  boolean $or
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $query = $this->query();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $count = count($value);
                if ($count === 3) {
                    list($field, $operator, $search) = $value;
                    $query = (!$or)
                        ? $query->where($field, $operator, $search)
                        : $query->orWhere($field, $operator, $search);

                } elseif ($count === 2) {
                    list($field, $search) = $value;
                    $query = (!$or)
                        ? $query->where($field, $search)
                        : $query->orWhere($field, $search);
                }
            } else {
                $query = (!$or)
                    ? $query->where($field, '=', $value)
                    : $query->orWhere($field, '=', $value);
            }
        }

        return $query->get($columns);
    }

    /**
     * Destroy the entity
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->query()->destroy($id);
    }

    /**
     * Create query
     *
     * @return Builder
     */
    public function query()
    {
        return call_user_func(static::MODEL . '::query');
    }

    /**
     * Create entities or update entities if it's exist
     *
     * @param String $idName
     * @param array  $data
     *
     * @return mixed
     */
    public function createOrUpdate(String $idName, array $data)
    {
        return $this->query()->updateOrCreate([ $idName => $data[$idName] ], $data);
    }

    /**
     * Get last insert item
     *
     * @return mixed
     */
    public function getLastInsertItem()
    {
        return $this->query()->orderBy($this->getPrimaryKeyName(), 'desc')->first();
    }

    /**
     * Get primary key name
     *
     * @return mixed
     */
    protected function getPrimaryKeyName()
    {
        return app(static::MODEL)->getKeyName();
    }
}
