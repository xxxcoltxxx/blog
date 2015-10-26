<?php

namespace Data\Handlers;

class Html
{
    public static function encode($text)
    {
        return htmlentities($text, ENT_COMPAT, "utf8");
    }
}