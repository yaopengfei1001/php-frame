<?php

namespace App\middleware;

class WebMiddleware
{
    public function handle($request, \Closure $next)
    {
        echo 'web middleware';
        return $next($request);
    }
}