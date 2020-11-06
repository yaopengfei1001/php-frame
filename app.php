<?php

use App\exceptions\HandleExceptions;
use App\middleware\WebMiddleware;
use core\Config;
use core\Database;
use core\log\Logger;
use core\PipeLine;
use core\query\QueryBuilder;
use core\Response;
use core\RouteCollection;
use core\view\Blade;
use core\view\Thinkphp;
use core\view\ViewInterface;
use Psr\Container\ContainerInterface;

define('FRAME_BASE_PATH', __DIR__);
define('FRAME_START_TIME', microtime(true));
define('FRAME_START_MEMORY', memory_get_usage());

class App implements ContainerInterface
{
    /**
     * 绑定关系
     * @var array
     */
    public $binding = [];

    /**
     * 类的实例
     * @var
     */
    private static $instance;

    /**
     * 所有实例的存放
     * @var array
     */
    protected $instances = [];

    private function __construct()
    {
        // 实例
        self::$instance = $this;
        // 注册绑定
        $this->register();
        //启动
        $this->boot();
    }

    public function get($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }


        // 服务是闭包， 加（）可以执行
        $instance = $this->binding[$abstract]['concrete']($this);

        if ($this->binding[$abstract]['is_singleton']) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    public function has($id)
    {
        // TODO: Implement has() method.
    }

    public static function getContainer()
    {
        return self::$instance ?? self::$instance = new self();
    }

    public function bind($abstract, $concrete, $is_singleton = false)
    {
        if (!$concrete instanceof Closure) {
            $concrete = function ($app) use ($concrete) {
                return $app->build($concrete);
            };
        }

        $this->binding[$abstract] = compact('concrete', 'is_singleton');
    }

    protected function getDependencies($paramters)
    {
        $dependencies = [];
        foreach ($paramters as $paramter) {
            if ($paramter->getClass()) {
                $dependencies[] = $this->get($paramter->getClass()->name);
            }
        }

        return $dependencies;
    }

    /**
     * @param $concrete
     * @return object
     * @throws ReflectionException
     */
    public function build($concrete)
    {
        // 反射
        $reflector = new ReflectionClass($concrete);
        // 获取构造函数
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            // 返回实例
            return $reflector->newInstance();
        }

        // 获取构造函数的参数
        $dependencies = $constructor->getParameters();

        // 当前类的所有实例化的依赖
        $instances = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    protected function register()
    {
        $registers = [
            'response' => Response::class,
            'router' => RouteCollection::class,
            'pipeline' => PipeLine::class,
            'config' => Config::class,
            'db' => Database::class,
            ViewInterface::class => Blade::class,
//            ViewInterface::class => Thinkphp::class,
            'log' => Logger::class,
            'exception' => HandleExceptions::class,
        ];

        foreach ($registers as $name => $concrete) {
            $this->bind($name, $concrete, true);
        }
    }

    protected function boot()
    {
        App::getContainer()->get('config')->init();

        App::getContainer()->get('router')->group([
            'namespace' => 'App\\controller',
//            'middleware' => [
//                WebMiddleware::class,
//            ]
        ], function ($router) {
            require_once FRAME_BASE_PATH . '/routes/web.php';
        });

        App::getContainer()->get('router')->group([
            'namespace' => 'App\\controller',
            'prefix' => 'api'
        ], function ($router) {
            require_once FRAME_BASE_PATH . '/routes/api.php';
        });

        App::getContainer()->get(ViewInterface::class)->init();
        App::getContainer()->get('exception')->init();
    }
}