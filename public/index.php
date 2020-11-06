<?php

use core\request\PhpRequest;
use core\request\RequestInterface;

require __DIR__ . '/../vendor/autoload.php';

// 简陋模拟自动加载功能  自动加载 = spl_autoload_register + include
//spl_autoload_register(function ($class) {
//    $psr4 = [
//        "App" => "app"
//    ];
//    $suffix = '.php';
//    foreach ($psr4 as $name =>$value) {
//        $class = str_replace($name, $value, $class);
//        include($class . $suffix);
//    }
//    var_dump($class);exit();
//});

//(new \App\User())->php();

require_once __DIR__ . '/../app.php';


app()->bind(RequestInterface::class, function () {
    return PhpRequest::create($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $_SERVER);
});


app()->get('response')->setContent(
    app('router')->dispatch(
        app()->get(RequestInterface::class)
    )
)->send();

//class User
//{
//    public $a = 'bbb';
//
//    public function index()
//    {
//        return 'aaa';
//    }
//
//    public function __toString()
//    {
//       return $this->a;
//    }
//}
//
//class Doctor
//{
//    public $user;
//
//    public function __construct(User $user)
//    {
//        $this->user = $user;
//    }
//
//    public function getUser()
//    {
//        return $this->user->index();
//    }
//}
//
//App::getContainer()->bind('user', User::class);
//
//App::getContainer()->bind('doctor', Doctor::class);
//
//echo App::getContainer()->get('user');
//
//print_r(App::getContainer()->get('doctor'));