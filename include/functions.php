<?php

/**
 * Функция для быстрого вывода инфы
 *
 * @param $array
 */
function pr($array) {
    $cli = strpos(php_sapi_name(), "cli") !== false;
    if (!$cli) {
        echo "<pre>";
    }
    if (is_bool($array) || is_null($array)) {
        var_dump($array);
    } else {
        print_r($array);
    }
    if ($cli) {
        echo "\n";
    } else {
        echo "</pre>";
    }
}
