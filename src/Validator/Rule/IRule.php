<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 12.11.2018
 * Time: 15:09
 */

namespace Jss\Validator\Rule;


interface IRule
{

    public function getParameters();

    public function validate($value);
}