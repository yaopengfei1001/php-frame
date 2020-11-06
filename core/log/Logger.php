<?php

namespace core\log;

use core\log\driver\DailyLogger;
use core\log\driver\StackLogger;

class Logger
{
    protected $config;

    // 所有的实例化的通道  就是多例而已
    protected $channels = [];

    public function __construct()
    {
        $this->config = app('config')->get('log');
    }

    public function channel($name = null)
    {
        if (!$name) {
            $name = $this->config['default'];
        }

        if (isset($this->channels[$name])) {
            return $this->channels[$name];
        }

        $config = app('config')->get('log.channels.' . $name);

        return $this->channels[$name] = $this->{'create' . ucfirst($config['driver'])}($config);
    }

    public function createStack($config)
    {
        return new StackLogger($config);
    }

    public function createDaily($config)
    {
        return new DailyLogger($config);
    }

    public function __call($name, $arguments)
    {
        return $this->channel()->$name(...$arguments);
    }
}