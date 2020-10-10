<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 12.11.2018
 * Time: 15:09
 */

namespace Jss\Form\Validator\Rule;


interface IRule
{

    public function getParameters();

    public function validate($value);

    public function setErrorMessage($message);

    public function setParameters(array $parameters = array());

    public function getErrorMessage();

    public function getJavasriptCode();

}