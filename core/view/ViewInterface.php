<?php

namespace core\view;

interface ViewInterface
{
    public function init();

    function render($path);
}