<?php

namespace core\view;

use duncan3dc\Laravel\BladeInstance;

class Blade implements ViewInterface
{
    /** @var BladeInstance $template */
    protected $template;

    public function init()
    {
       $config = app('config')->get('view');

       $this->template = new BladeInstance($config['view_path'], $config['cache_path']);
    }

    function render($path, $params = [])
    {
        return $this->template->render($path, $params);
    }
}