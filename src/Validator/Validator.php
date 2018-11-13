<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 06.11.2018
 * Time: 15:15
 */

namespace Jss\Form\Validator;


class Validator
{

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public static function min($value, $min)
    {
        return $value >= $min;
    }

    public static function max($value, $max)
    {
        return $value <= $max;
    }

    public static function range($value, $min, $max)
    {
        return (self::min($value, $min) && self::max($value, $max));
    }

    public static function equal($value, $equalTo)
    {
        return $value==$equalTo;
    }

    public static function notEqual($value, $equalTo)
    {
        return !self::equal($value,$equalTo);
    }

    public static function filled($value, $max)
    {
        if(is_null($value) || $value === '') return false;
        return true;
    }


}