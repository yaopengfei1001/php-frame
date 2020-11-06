<?php

use core\TestCase;

class ExampleTest extends TestCase
{
    public function testDatabaseDefault()
    {
        $this->assertEquals('mysql_one', config('database.default'));
    }

    public function testGetRoute()
    {
        $this->get('/hello')->assertStatusCode(200);
    }

    public function testPostRoute()
    {
        $res = $this->get('/hello');

        $this->assertEquals('你在访问hello', $res->getContent());
    }
}