<?php
namespace System;

class Controller
{
    /** @var View */
    protected $view;
    protected $flash;
    protected $referer;

    public function __construct()
    {
        $this->view = new View();
        $this->flash = $this->getFlash();
        $this->referer = empty($_SERVER['HTTP_REFERER']) ? "/" : $_SERVER['HTTP_REFERER'];
    }

    protected function redirect($url, $message = null)
    {
        if ($message) {
            $this->setFlash($message);
        }
        header("Location: {$url}");
        die();
    }

    public function setFlash($message)
    {
        $_SESSION['flash'] = $message;
    }

    public function getFlash()
    {
        if (!empty($_SESSION['flash'])) {
            $message = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $message;
        } else {
            return null;
        }
    }
}