<?php
namespace Data\Providers;

class Config {
    /** @var Config[] */
    private static $instance = array();
    private $data = array();

    private function __construct($filename)
    {
        $this->data = parse_ini_file($filename, true);
    }

    public static function instance($name)
    {
        if (!preg_match("#^[a-z_]+$#", $name)) {
            throw new \Exception("Некорректное имя файла {$name}", 400);
        }
        $filename = PATH_CONFIG . $name . ".ini";
        if (!file_exists($filename)) {
            throw new \Exception("Конфигурация {$name} не найдена", 404);
        }
        if (!isset(self::$instance[$name])) {
            self::$instance[$name] = new self($filename);
        }
        return self::$instance[$name];
    }

    public function get($section, $name = null, $default = null)
    {
        if (is_null($default) && is_null($name) && !isset($this->data[$section]) || !is_null($name) && !isset($this->data[$section][$name])) {
            throw new \Exception("Параметр {$name} не найден в разделе {$section}", 404);
        }
        return is_null($name) ? $this->data[$section] : $this->data[$section][$name];
    }

    public function getData()
    {
        return $this->data;
    }
}