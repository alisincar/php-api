<?php


namespace API\src;

use API\src\Helpers\Helper;
use API\src\user\Login;
use API\src\user\Register;

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

class Router
{
    public $router = null;

    public function __construct()
    {
        $this->router = new \Bramus\Router\Router();
        $this->route();

    }

    private function route()
    {

        $router = $this->router;

        $router->setNamespace('API\src');
        $router->set404(function () {
            Helper::response('wrongMethod');
        });

        $router->post('/login/{tip}', function ($tip)  {
            new Login($tip, $_POST);
        });

        $router->post('/register/{tip}', function ($tip) {
            new Register($tip, $_POST);
        });

        $router->mount('/login', function () use ($router) {

            $router->post('/', function () {
                echo 'add movie';
            });
        });
        $router->run();
    }

}