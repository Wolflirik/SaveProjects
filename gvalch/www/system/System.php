<?php

namespace system;

use system\core\router\DispatchedRoute;
use system\helper\Common;

class System{

    /**
     * @var $container
     */
    private $container;

    /**
     * @var $router
     */
    private $router;

    /**
     * System constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->router = $this->container->get('router');
    }

    public function start()
    {
        try
        {
            require_once __DIR__ . '/../' . LOCATION . '/routes.php';
            $routerDispatch = $this->router->dispatch(Common::getMethod(), Common::getPathUri());
            /**if(preg_match('~admin~', Common::getPathUri()))
            {
                $this->container->get('auth')->check(!preg_match('~auth~', Common::getPathUri()));
            }**/
            if($routerDispatch == null)
            {
                $routerDispatch = new DispatchedRoute('common\Error:index');
            }
            list($class, $action) = explode(':', $routerDispatch->getController(), 2);
            $controller = "\\" . LOCATION . "\\controller\\" . $class;
            if (class_exists($controller))
            {
                call_user_func_array([new $controller($this->container), $action], $routerDispatch->getParams());
            }
            else
            {
                exit(sprintf('Controller %s not found!', $controller));
            }
        }
        catch(\Exception $e)
        {
            exit($e->getMessage());
        }

    }
}