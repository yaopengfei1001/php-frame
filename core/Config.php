<?php

namespace core;

class Config
{
    protected $config = [];

    public function init()
    {
        foreach (glob(FRAME_BASE_PATH . '/config/*.php') as $file) {
            $key = str_replace('.php', '', basename($file));
            $this->config[$key] = require $file;
        }
    }

    public function get($key)
    {
        $keys = explode('.', $key);
        $config = $this->config;

        foreach ($keys as $key) {
            $config = $config[$key];
        }

        return $config;
    }

    public function set($key, $val)
    {
        $keys = explode('.', $key);

        $newConfig = &$this->config;
        foreach ($keys as $key) {
            $newConfig = &$newConfig[$key];
        }

        $newConfig = $val;
    }
}