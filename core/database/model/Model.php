<?php

namespace core\database\model;

class Model
{
    protected $connection;

    protected $table;

    protected $primaryKey;

    protected $timestamps = true;

//     为什么要分开两个属性?
//     $orginal 原的数据
//     $attriubte 原数据的复制版 用户只能修改这个 !
//     然后跟$original相比较 得出用户修改的数据字段
    protected $original;
    protected $attribute;

    public function __construct()
    {
        $this->connection = app('db')->connection($this->connection);
    }

    public function getTable()
    {
        if ($this->table) {
            return $this->table;
        }

        $className = get_class($this);
        $classArr = explode('\\', $className);

        $table = lcfirst(end($classArr));

        return $table . 's';
    }

    public function setOriginalValue($key, $val)
    {
        if (!$this->original) {
            $this->original = new \stdClass();
        }

        $this->original->$key = $val;
    }

    public function setAttribute($key, $val)
    {
        if (!$this->attribute) {
            $this->attribute = new \stdClass();
        }

        $this->attribute->$key = $val;
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function syncOriginal()
    {
        $this->attribute = $this->original;
    }

    public function diff()
    {
        $diff = [];
        if ($this->attribute == $this->original) {
            return $diff;
        }

        foreach ($this->original as $origin_key => $origin_val) {
            if ($this->attribute->$origin_key != $origin_val) {
                $diff[$origin_key] = $this->attribute->$origin_key;
            }
        }

        return $diff;
    }

    public function __get($name)
    {
        return $this->attribute->$name;
    }

    public static function __callStatic($method, $args)
    {
        return (new static())->$method(...$args);
    }

    public function __call($method, $args)
    {
        return (new Builder($this->connection->newBuilder()))->setModel($this)->$method(...$args);
    }
}