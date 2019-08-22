<?php


namespace API\v1\src;

use API\v1\src\Helpers\Helper;
use API\v1\src\user\Login;
use API\v1\src\user\Register;
use API\v1\src\user\User;

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

        $router->setNamespace('API\v1\src');
        $router->set404(function () {
            Helper::response('wrongMethod');
        });

        $router->post('/login/{tip}', function ($tip) {
            new Login($tip, $_POST);
        });

        $router->post('/register/{tip}', function ($tip) {
            new Register($tip, $_POST);
        });

        $router->mount('/user', function () use ($router) {

            $router->post('/me', function () {
                $user = new User();
                if (isset($_POST['token']) && !empty($_POST['token'])) {
                    $user->getUser($_POST['token']);
                } else {
                    Helper::response('emptyParams');
                }
            });
        });
        $router->run();
    }

}