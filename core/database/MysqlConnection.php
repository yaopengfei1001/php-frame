<?php

namespace core\database;

use core\query\MysqlGrammar;
use core\query\QueryBuilder;

class MysqlConnection extends Connection
{
    protected static $connection;

    public function getConnection()
    {
        return self::$connection;
    }

    public function select($sql, $bindings = [], $useReadPod = true)
    {
        $statement = $this->pdo;
        $sth = $statement->prepare($sql);
        try {
            $sth->execute($bindings);
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function __call($method, $parameters)
    {
        return $this->newBuilder()->$method(...$parameters);
    }

    public function newBuilder()
    {
        return new QueryBuilder($this, new MysqlGrammar());
    }
}