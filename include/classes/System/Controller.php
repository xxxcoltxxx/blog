<?php
namespace System;

class Controller
{
    /** @var View */
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        die();
    }
}