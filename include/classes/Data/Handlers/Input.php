<?php
namespace Data\Handlers;

/**
 * Class Input
 *
 * Класс для работы с $_REQUEST
 */
class Input implements \ArrayAccess
{
    private $post;
    private $get;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
    }

    public function get($key, $default = null, $exception = null)
    {
        if (isset($this->post[$key])) {
            return $this->post[$key];
        }
        elseif (isset($this->get[$key])) {
            return $this->get[$key];
        }
        else {
            if ($exception) {
                if (!($exception instanceof \Exception)) $exception = new \Exception($exception);
                throw $exception;
            }
            else {
                return $default;
            }
        }
    }

    public function getInt($key, $default = null, $exception = null)
    {
        $value = $this->get($key, $default, $exception);
        return $value === $default ? $value : (int) $value;
    }

    public function getBool($key, $default = null, $exception = null)
    {
        $value = $this->get($key, $default, $exception);
        return $value === $default ? $value : (bool) $value;
    }

    public function getStr($key, $default = null, $exception = null)
    {
        $str = $this->get($key, $default, $exception);
        return $str !== null ? trim($str) : null;
    }

    public function getArray($key, $default = array(), $exception = null)
    {
        $result = $this->get($key, $default, $exception);
        if ($result && is_array($result)) {
            return $result;
        }
        else {
            return $default;
        }
    }

    public function getTimestamp($key, $default = null, $exception = null)
    {
        $var = strtotime($this->get($key, $default, $exception));
        return $var === false ? $default : $var;
    }

    public function getFile($key, $default = null, $exception = null)
    {
        if (@is_array($_FILES[$key]['name'])) {
            $result = array();
            for ($i = 0; $i < count($_FILES[$key]['name']); $i++) {
                if (!$_FILES[$key]['error'][$i] && is_uploaded_file($_FILES[$key]['tmp_name'][$i])) {
                    $result[] = $_FILES[$key]['tmp_name'][$i];
                }
            }
            return $result;
        }
        else {
            if (@$_FILES[$key] && !@$_FILES[$key]['error'] && is_uploaded_file(@$_FILES[$key]['tmp_name'])) {
                return $_FILES[$key]['tmp_name'];
            }
            elseif ($exception) {
                throw new Exception($exception);
            }
            else {
                return $default;
            }
        }
    }

    public function toArray()
    {
        return array_merge($this->get, $this->post);
    }

    public function exists($key)
    {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }

    public function offsetSet($offset, $value) {}

    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    public function offsetUnset($offset) {}

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}
