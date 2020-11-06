<?php

namespace core\log\driver;

use Psr\Log\AbstractLogger;

class StackLogger extends AbstractLogger
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        return strtr($message, $replace);
    }

    public function log($level, $message, array $context = array())
    {
        if (is_array($message)) {
            $message = var_export($message, true) . var_export($context, true);
        } elseif (is_string($message)) {
            $message = $this->interpolate($message, $context);
        }

        $message = sprintf($this->config['format'], date('y-m-d h:m:s'), $level, $message);

        error_log($message . PHP_EOL, 3, $this->config['path'] . '/php_frame.log');
    }
}