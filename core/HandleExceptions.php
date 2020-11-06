<?php

namespace core;

class HandleExceptions
{
    // 要忽略记录的异常 不记录到日志去
    protected $ignore = [];

    public function init()
    {
        // 所有异常到托管到handleException方法
        set_exception_handler([$this, 'handleException']);

        // 所有错误到托管到handleError方法
        set_error_handler([$this, 'handleError']);
    }

    public function handleError($error_level, $error_message, $error_file, $error_line, $error_context)
    {
        app('response')->setContent('error')->setCode(500)->send();

        app('log')->error($error_message. ' at ' . $error_file . ':' . $error_line);
    }

    public function handleException(\Throwable $e)
    {
        if (method_exists($e, 'render')) {
            app('response')->setContent($e->render())->send();
        }

        if (!$this->isIgnore($e)) {
            app('log')->debug($e->getMessage().' at '.$e->getFile().':'.$e->getLine());

            echo $e->getMessage().' at '.$e->getFile().':'.$e->getLine();
        }
    }

    protected function isIgnore(\Throwable $e)
    {
        foreach ($this->ignore as $item) {
            if ($item == get_class($e)) {
                return true;
            }
        }

        return false;
    }
}