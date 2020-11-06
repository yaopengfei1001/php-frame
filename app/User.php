<?php

namespace App;

use core\database\model\Model;

class User extends Model
{
    protected $table = 'user';

    public function php()
    {
        return "helle php";
    }

    public function sayPhp()
    {
        return "id={$this->id} 名字：{$this->name} 说了， " . $this->php();
    }
}