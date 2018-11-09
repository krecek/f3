<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 09.11.2018
 * Time: 9:53
 */

namespace Jss\Form\Elements;


class FormHash extends FormInputHidden
{
    public function __construct($name, string $value = '')
    {
        parent::__construct($name, $value);
        $_SESSION['form_' . $value] = time();
    }

    public function validate()
    {
        $hash = $this->sendValue;
        if(!isset($_SESSION["form_$hash"]))
        {
            $this->setError('Selhala kontrola formuláře');
            return true;
        }
        unset($_SESSION["form_$hash"]);
        foreach($_SESSION as $key => $time) if(preg_match('~^form_~', $key) && strtotime('-15 minutes') > $time) unset($_SESSION[$key]);
        return true;
    }
}