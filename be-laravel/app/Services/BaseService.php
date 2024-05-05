<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;

abstract class BaseService
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model(): mixed;

    /**
     * Set Eloquent Model to instantiate
     *
     * @return void
     */
    private function setModel(): void
    {
        $newModel = App::make($this->model());

        if (!$newModel instanceof Model)
            throw new \RuntimeException("Class {$newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        $this->model = $newModel;
    }

    /**
     * @param $params
     * @param array $relations
     * @param bool $withTrashed
     * @return LengthAwarePaginator
     */
    public function paginate($params = null, array $relations = [], bool $withTrashed = false): LengthAwarePaginator
    {
        $params = $params ?: request()->toArray();
        $limit = $params['per_page'] ?? 20;

        $query = $this->buildBasicQuery($params, $relations, $withTrashed);
        return $query->latest('id')->paginate($limit);
    }

        /**
     * @param $params
     * @param array $relations
     * @param bool $withTrashed
     * @return Builder
     */
    public function buildBasicQuery($params = null, array $relations = [], bool $withTrashed = false): Builder
    {
        $query = $this->model->query();
        $params = $params ?: request()->toArray();

        $relations = $this->getRelations($relations);
        if ($relations && count($relations)) {
            $query->with($relations);
        }

        if ($withTrashed && in_array(SoftDeletes::class, class_uses($this->model)) && method_exists($query, 'withTrashed')) {
            $query->withTrashed();
        }

        // if (method_exists($this, 'addFilter')) {
        //     $this->addFilter($query, $params);
        // }

        return $query;
    }

    /**
     * Getter for relations.
     */
    public function getRelations(array $relations = []): array
    {
        return array_merge([], $relations);
    }
}
