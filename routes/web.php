<?php

/** @var RouteCollection $router */

use App\exceptions\ErrorMessageException;
use App\middleware\WebMiddleware;
use App\User;
use core\RouteCollection;

$router->get('hello', function () {
    return '你在访问hello';
})->middleware(WebMiddleware::class);

$router->get('config', function () {
    echo app('config')->get('database.connections.mysql_one.driver') . '<hr/>';
    app('config')->set('database.connections.mysql_one.driver', 'mysql_set');
    echo app('config')->get('database.connections.mysql_one.driver');
});

$router->get('db', function () {
   var_dump(app('db')->select('select * from user where id = ?', [1]));
});

$router->get('query', function () {
    var_dump(app('db')->table('user')->where('id', 1)->get());
});

$router->get('model', function () {
    $users = User::where('id', 1)->get();
    foreach ($users as $user) {
        /** @var User $user */
        echo $user->sayPhp() . '<br/>';
    }
});

$router->get('controller', 'UserController@index');

$router->get('view/blade', function () {
    $str = 'blade';

    return view('blade.index', compact('str'));
});

$router->get('view/thinkphp', function () {
    $str = 'thinkphp';

    return view('thinkphp.index', compact('str'));
});

$router->get('log/stack', function () {
    app('log')->debug('i love you, {name}', ['name' => 'zy']);
    app('log')->info('hello world!');
});

$router->get('exception', function () {
    throw new ErrorMessageException('error exception');
});