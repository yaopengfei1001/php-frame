<?php

namespace core;

class Response
{
    protected $headers = [];

    protected $content = '';

    protected $code = 200;

    public function sendContent()
    {
        echo $this->content;
    }

    public function sendHeaders()
    {
        if (is_array($this->headers)) {
            echo '';
        } else {
            echo $this->headers;
        }
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    public function setContent($content)
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }

        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

}