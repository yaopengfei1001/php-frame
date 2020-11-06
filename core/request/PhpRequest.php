<?php


namespace core\request;


class PhpRequest implements RequestInterface
{
    protected $url;

    protected $method;

    protected $headers;

    public function __construct($uri, $method, $headers)
    {
        $this->url = $uri;
        $this->method = $method;
        $this->headers = $headers;
    }

    public static function create($uri, $method, $headers)
    {
        return new static($uri, $method, $headers);
    }

    public function getUri()
    {
        return $this->url;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHeader()
    {
        // TODO: Implement getHeader() method.
    }
}