<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
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
}
