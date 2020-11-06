<?php

namespace core\database\model;

class Builder
{
    protected $query;

    protected $model;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    public function __call($method, $args)
    {
        $this->query->$method(...$args);
        return $this;
    }

    public function get($columns = ['*'])
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }

        $this->query->columns = $columns;
        $this->query->table($this->model->getTable());
        $sql = $this->query->toSql();

        return $this->bindModel($this->query->runSql($sql));
    }

    protected function bindModel($datas)
    {
        if (!is_array($datas)) {
            $datas[] = $datas;
        }

        $models = [];
        foreach ($datas as $data) {
            $model = clone $this->model;
            foreach ($data as $key => $val) {
                $model->setOriginalValue($key, $val);
            }
            $model->syncOriginal();
            $models[] = $model;
        }

        return $models;
    }
}