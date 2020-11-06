<?php

namespace core\view;

use think\Template;

class Thinkphp implements ViewInterface
{
    /** @var Template $template */
    protected $template;

    public function init()
    {
        $config = app('config')->get('view');

        $this->template = new Template([
            'view_path' => $config['view_path'],
            'cache_path' => $config['cache_path'],
        ]);
    }

    /**
     * @param $path
     * @param array $params
     */
    function render($path, $params = [])
    {
        $this->template->assign($params);
        $path = str_replace('.', '/', $path);
        $this->template->fetch($path);
    }
}