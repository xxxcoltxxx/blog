<?php

/**
 * Автозагрузчик
 *
 * @param $class_name
 * @return bool|mixed
 */
function autoLoader($class_name)
{
    $class_name = ltrim($class_name, '\\');
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . ".php";
    $class_filename = array_pop(explode(DIRECTORY_SEPARATOR, $path));

    if (strpos($class_filename, "Controller") > 0) {
        $filename = PATH_APP_CONTROLLERS . $path;
    } elseif (strpos($class_filename, "Model") > 0) {
        $filename = PATH_APP_MODELS . $path;
    } else {
        $filename = PATH_CLASSES . $path;
    }

    if (file_exists($filename)) {
        return require_once $filename;
    } else {
        return false;
    }
}
