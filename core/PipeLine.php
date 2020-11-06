<?php

namespace core;

class PipeLine
{
    protected $classes = [];

    protected $handleMethod = 'handle';

    /**
     * 因为容器是单例，所有要创建个新的对象
     * @return PipeLine
     */
    public function create()
    {
        return clone $this;
    }

    public function setHandleMethod($method)
    {
        $this->handleMethod = $method;
        return $this;
    }

    public function setClass($class)
    {
        $this->classes = $class;
        return $this;
    }

    public function run(\Closure $initial)
    {
        return array_reduce(array_reverse($this->classes), function ($res, $currClass) {
            return function ($request) use ($res, $currClass) {
                return (new $currClass)->{$this->handleMethod}($request, $res);
            };
        }, $initial);
    }
}