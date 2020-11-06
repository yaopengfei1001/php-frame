<?php

namespace core\database;

class Connection
{
    protected $pdo;

    protected $tablePrefix;

    protected $config;

    public function __construct($pdo, $config)
    {
        $this->pdo = $pdo;
        $this->tablePrefix = $config['prefix'];
        $this->config = $config;
    }
}