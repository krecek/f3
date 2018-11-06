<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 06.11.2018
 * Time: 15:15
 */

namespace Jss\Validator;


class Validator
{

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public static function validateMin($value, $min)
    {
        return $value >= $min;
    }
}