<?php
use System\Engine;

require_once "../include/include.php";

try {
    $engine = Engine::instance();
    $engine->run();
} catch (Exception $e) {
    pr($e->getMessage());
}
