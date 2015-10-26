<?php
namespace Data\Handlers;

/**
 * Валидация данных
 */

class Validate
{
    public static function check($condition, $message, $code = 400)
    {
        if (!$condition) {
            throw new \Exception($message, $code);
        }
    }

    /**
     * Проверка на число
     * @param $var              mixed       Переменная для проверки
     * @param $min              null|int    Минимальное допустимое значение (null - не проверять)
     * @param $max              null|int    Максимальное допустимое значение (null - не проверять)
     * @param $message          string      Сообщение для Exception, если проверка не пройдена
     * @throws \Exception
     */
    public static function int($var, $min = null, $max = null, $message = "Bad request")
    {
        self::check(is_numeric($var) && (is_null($min) || !is_null($min) && $var >= $min) && (is_null($max) || !is_null($max) && $var <= $max), $message);
    }

    /**
     * Проверка на строку
     *
     * @param $string           mixed       Переменная для проверки
     * @param $min              null|int    Минимальное допустимое значение (null - не проверять)
     * @param $max              null|int    Максимальное допустимое значение (null - не проверять)
     * @param $message          string      Сообщение для Exception, если проверка не пройдена
     * @throws \Exception
     */
    public static function length($string, $min = null, $max = null, $message = "Bad request")
    {
        self::check(
            (is_string($string) || is_numeric($string))
            && (is_null($min) || !is_null($min) && strlen($string) >= $min)
            && (is_null($max) || !is_null($max) && strlen($string) <= $max),
            $message
        );
    }
}
