<?php
spl_autoload_register('autoload');
function autoload($class)
{
    if (preg_match('~^Jss\\\Form\\\(.*)~', $class, $tmp))
    {
        $filename = __DIR__ . "/".str_replace('\\','/',$tmp[1]).".php";
        if (file_exists($filename)) require_once $filename;

    }
}
