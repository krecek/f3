<?php
spl_autoload_register('autoload_jss_form');
function autoload_jss_form($class)
{
    if (preg_match('~^Jss\\\Form\\\(.*)~', $class, $tmp))
    {
        $filename = __DIR__ . "/" . str_replace('\\', '/', $tmp[1]) . ".php";
        if (file_exists($filename)) require_once $filename;

    }
}
