<?php

define("PATH_ROOT", realpath(__DIR__ . "/..") . "/");
define("PATH_INCLUDE", PATH_ROOT . "include/");
define("PATH_CLASSES", PATH_INCLUDE . "classes/");
define("PATH_CONFIG", PATH_INCLUDE . "config/");

define("PATH_APP", PATH_ROOT . "app/");
define("PATH_APP_TEMPLATES", PATH_APP . "views/");
define("PATH_TEMPLATES", PATH_INCLUDE . "views/");
define("PATH_APP_CONTROLLERS", PATH_APP . "controllers/");
define("PATH_APP_MODELS", PATH_APP . "models/");

define("URL_CSS", "/public/css/");
define("URL_JS", "/public/js/");
define("URL_LIBS", "/public/libs/");

define("MYSQL_DATE_TIME", "Y-m-d H:i:s");
define("DISPLAY_DATE_TIME", "d.m.Y в H:i");
