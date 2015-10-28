<?php
namespace System;

class View
{
    public $charset = "UTF-8";
    public $content_type = "text/html";

    public $title = "";
    private $output = "";
    public $layout = "default";
    public $css = array();
    public $js = array();

    public function __construct()
    {
        $this->addLib("jquery");
        $this->addLib("bootstrap");
        $this->addJS("/public/js/functions.js");
    }

    private function buildHead()
    {
        $this->js = array_map("unserialize", array_unique(array_map("serialize", $this->js)));
        $this->css = array_map("unserialize", array_unique(array_map("serialize", $this->css)));
        return $this->template("head", ['js' => $this->js, 'css' => $this->css]);
    }

    public function addCSS($src)
    {
        if ((strpos($src, "http:") !== 0) && (strpos($src, "/") !== 0)) {
            $src = URL_CSS . $src . ".css";
        }
        $this->css[] = $src;
    }

    public function addJS($src, $charset = "")
    {
        if (strpos($src, "http") !== 0 && strpos($src, "/") !== 0) {
            $src = URL_JS . $src;
        }
        $this->js[] = compact("src", "charset");
    }

    public function addLib($lib)
    {
        $url = URL_LIBS . $lib . "/";
        switch ($lib) {
            case "jquery":
                // jQuery
                $this->addJS($url . "jquery.min.js");
                return true;
            case "bootstrap":
                // Twitter Bootstrap
                $this->addLib("jquery");
                $this->addCSS($url . "css/bootstrap.min.css");
                $this->addJS($url . "js/bootstrap.min.js");
                return true;
            default:
                $this->addJS($url . $lib . ".js");
        }

        return false;
    }

    public function htmlPage($file, $data = null)
    {
        $this->output($file, $data);
        header("Content-type: {$this->content_type}; charset={$this->charset}");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        return $this->template("layouts/{$this->layout}", array(
            'head' => $this->buildHead(),
            'content' => $this->output
        ));
    }

    public function ajaxPage()
    {
        header("Content-type: {$this->content_type}; charset={$this->charset}");
        return $this->output;
    }

    public function json($json)
    {
        $this->content_type = "application/json";
        $this->charset = "utf-8";
        $this->output = json_encode($json);
        return $this->output;
    }

    /**
     * @param string $file
     * @param bool|array $data
     */
    public function output($file, $data = null)
    {
        $this->output = $this->template($file, $data);
    }

    public function template($file, $data = null)
    {
        if (is_array($data)) {
            extract($data);
        }
        $app_filename = PATH_APP_TEMPLATES . $file . ".inc";
        $filename = PATH_TEMPLATES . $file . ".inc";
        ob_start();

        if (file_exists($app_filename)) {
            require $app_filename;
        } elseif (file_exists($filename)) {
            require $filename;
        } else {
            throw new \Exception("Template {$file} not found", 404);
        }
        $output = ob_get_clean();
        return $output;
    }
}
