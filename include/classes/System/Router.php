<?php
namespace System;

class Router
{
    public $controller;
    public $action;
    public $data;

    public function __construct($uri)
    {
        $url = parse_url($uri);
        $parts = explode("/", trim($url['path'], "/"));
        $this->controller = empty($parts[0]) ? "index" : $parts[0];
        $this->action = empty($parts[1]) ? "index" : $parts[1];
        $this->data = empty($parts[2]) ? [] : array_slice($parts, 2);
    }
}