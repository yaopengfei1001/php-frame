<?php

namespace core;

use core\request\PhpRequest;
use core\request\RequestInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $response;

    // 基境共享
    protected function setUp()
    {
        require_once __DIR__ . '/../app.php';
    }

    // 调用路由
    public function call($uri, $method)
    {
        \App::getContainer()->bind(RequestInterface::class, function () use ($uri, $method) {
            return PhpRequest::create($uri, $method, '');
        });

        return $this->response = app('response')->setContent(
            app('router')->dispatch(
                \App::getContainer()->get(RequestInterface::class)
            )
        );
    }

    public function get($uri, $params = [])
    {
        $this->call($uri, 'GET', $params);
        return $this;
    }

    public function post($uri,$params = [])
    {
        $this->call($uri,'POST',$params);
        return $this;
    }

    protected function assertStatusCode($status)
    {
        $this->assertEquals($status, $this->response->getStatusCode());
        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this->response->{$name}(... $arguments);
    }
}