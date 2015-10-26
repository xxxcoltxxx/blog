<?php

use System\Engine;

require_once "defines.php";
require_once PATH_INCLUDE . "functions.php";
require_once PATH_INCLUDE . "autoload.php";
require_once PATH_ROOT . "vendor/autoload.php";

spl_autoload_register("autoLoader");
$engine = Engine::instance();
