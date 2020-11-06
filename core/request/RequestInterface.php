<?php

namespace core\request;

interface RequestInterface
{
    /**
     * 初始化
     * RequestInterface constructor.
     * @param $uri
     * @param $method
     * @param $headers
     */
    public function __construct($uri, $method, $headers);

    /**
     * 创建
     * @param $uri
     * @param $method
     * @param $headers
     * @return mixed
     */
    public static function create($uri, $method, $headers);

    /**
     * 获取请求uri
     * @return mixed
     */
    public function getUri();

    /**
     * 获取请求方法
     * @return mixed
     */
    public function getMethod();

    /**
     * 获取请求头
     * @return mixed
     */
    public function getHeader();
}