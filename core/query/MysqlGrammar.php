<?php

namespace core\query;

class MysqlGrammar
{
    protected $selectComponents = [
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'lock',
    ];

    public function compileSql(QueryBuilder $query)
    {
        $sql = [];

        foreach ($this->selectComponents as $component) {
            if (isset($query->{$component})) {
                $sql[$component] = $this->$component($query, $query->$component);
            }
        }
        return implode($sql);
    }

    protected function columns(QueryBuilder $query, $columns)
    {
        if (!$columns) {
            $columns = ['*'];
        }

        $select = 'select ';
        if ($query->distinct) {
            $select = 'select distinct ';
        }

        return $select . implode(',', $columns);
    }

    protected function from(QueryBuilder $query, $from)
    {
        return ' from ' . $from;
    }

    protected function joins()
    {
        //todo
    }

    protected function wheres(QueryBuilder $query, $wheres)
    {
        if (!$wheres) {
            return '';
        }

        $whereArrays = [];
        foreach ($wheres as $index => $where) {
            if (!$index) {
                $where['joiner'] = ' where';
            }

            $whereArrays[] = sprintf(' %s `%s` %s ?', $where['joiner'], $where['column'], $where['operator']);
        }

        return implode(',', $whereArrays);
    }

    protected function groups()
    {

    }

    protected function havings()
    {

    }

    protected function orders()
    {

    }

    protected function limit()
    {

    }

    protected function offset()
    {

    }

    protected function lock()
    {

    }
}