<?php

namespace App\middleware;

use core\request\RequestInterface;

class ControllerMiddleware
{
    public function handle(RequestInterface $request, \Closure $next)
    {
        echo 'controller middleware';
        return $next($request);
    }

}