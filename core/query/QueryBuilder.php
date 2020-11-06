<?php

namespace core\query;

use core\database\Connection;

class QueryBuilder
{
    /** @var Connection $connection */
    protected $connection;

    protected $grammar;

    public $binds;

    public $columns;

    public $distinct;

    public $from;

    public $wheres;

    public $union;

    public $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'having' => [],
        'groupBy' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    protected $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'like', 'like binary', 'not like', 'ilike', '&', '|', '^',
        '<<','>>','rlike','not rlike','regexp','not regexp','~','~*','!~','!~*','similar to','not similar to',
        'not ilike','~~*','!~~*'
    ];

    public function __construct(Connection $connection, $grammar)
    {
        $this->connection = $connection;
        $this->grammar = $grammar; // 编译成sql的类
    }

    public function table(string $table, $as = null)
    {
       $this->from($table, $as);
       return $this;
    }

    public function from($table, $as)
    {
        $this->from = $as ? "{$table} as {$as}" : $table;
        return $this;
    }

    public function get($columns = ['*'])
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }
        $this->columns = $columns;
        $sql = $this->toSql();

        return $this->runSql($sql);
    }

    public function runSql($sql)
    {
        return $this->connection->select($sql, $this->getBinds());
    }

    public function where($column, $operator = null, $value = null, $joiner = 'and')
    {

        if (is_array($column)) {
            foreach ($column as $col => $value) {
                $this->where($col, '=', $value);
            }
        }

        if (!in_array($operator, $this->operators)) {
            $value = $operator;
            $operator = '=';
        }

        $type = 'Basic';
        $this->wheres[] = compact('type', 'column', 'operator', 'value', 'joiner');

        $this->binds[] = $value;

        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function find($id, $columns = ['*'], $key = 'id')
    {
        return $this->where($key, $id)->get($columns);
    }

    public function whereLike($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'like');
    }

    public function toSql()
    {
        return $this->grammar->compileSql($this);
    }

    public function getBinds()
    {
        return $this->binds;
    }
}