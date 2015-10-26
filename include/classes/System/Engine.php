<?php
namespace System;

use Data\Handlers\Input;
use Data\Handlers\Validate;

class Engine
{
    /** @var Input  */
    private $input;
    /** @var Router */
    private $router;
    /** @var Controller */
    private $controller;

    private static $instance;

    private function __construct()
    {
        $this->init();
    }

    private function init()
    {
        session_start();
        $this->input = new Input();
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $this->router = new Router($uri);
        $controller_class = ucfirst($this->router->controller) . "Controller";
        $action_method = "action" . ucfirst($this->router->action);

        Validate::check(class_exists($controller_class), "Controller {$controller_class} not found", 404);
        $this->controller = new $controller_class();
        Validate::check(method_exists($this->controller, $action_method), "Action {$action_method} not found", 404);
        echo call_user_func_array([$this->controller, $action_method], $this->router->data);
    }

    /**
     * @return Engine
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return Input
     */
    public static function getInput()
    {
        $engine = self::instance();
        return $engine->instance()->input;
    }
}
