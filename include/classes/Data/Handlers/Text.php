<?php

namespace Data\Handlers;

class Text
{
    private static $months = array(1 => "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
    public static function htmlEncode($text)
    {
        return htmlentities($text, ENT_COMPAT, "utf8");
    }

    /**
     * Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
     * @param  $number Integer Число на основе которого нужно сформировать окончание
     * @param  $endingsArray  Array Массив слов или окончаний для чисел (1, 4, 5),
     * @return String
     */
    public static function getNumEnding($number, $ending_array)
    {
        $number = $number % 100;
        if ($number >= 11 && $number <= 19)
            $ending = $ending_array[2];
        else
        {
            $i = $number % 10;
            switch ($i)
            {
                case 1: $ending = $ending_array[0]; break;
                case 2:
                case 3:
                case 4: $ending = $ending_array[1]; break;
                default: $ending = $ending_array[2];
            }
        }
        return $ending;
    }

    /**
     * Обрезает строку до заданной длины
     *
     * @param string $string исходная строка
     * @param int $max_len максимальная длина строки
     * @param bool $dots добавлять ли многоточие, если длина строки больше максимальной
     * @return string
     */
    public static function cutStr($string, $max_len, $dots = true)
    {
        $text = $string;
        if(mb_strlen($string, "utf8") > $max_len) {
            $text = preg_replace('#^(.{0,' . $max_len . '}[a-zА-я0-9]*).*$#uis', "$1", $text, 1);
            if ($dots && mb_strlen($string, "utf8") > $max_len) {
                $text .= "...";
            }
        }

        return trim($text);
    }

    /**
     * Форматирует дату
     *
     * @param string $timestamp
     * @param bool $date
     * @param bool $time
     * @return int
     */
    public static function formatDate($timestamp, $date = true, $time = true)
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->format("U");
        }

        $info = getdate($timestamp);
        $date = $date ? date("d.m.Y", $timestamp) : "";
        $time = $time ? date("H:i", $timestamp) : "";

        if ($date == date("d.m.Y")) {
            return "сегодня" . ($time ? ", в {$time}" : "");
        } else {
            if ($date == date("d.m.Y", strtotime("-1 day"))) {
                return  "вчера" . ($time ? ", в {$time}" : "");
            } else {
                $d = $info['mday'] . " " . self::$months[$info['mon']];
                if ($info['year'] != date("Y")) {
                    $d .= " " . $info['year'];
                }
                return $d . " " . $time;
            }
        }
    }
}