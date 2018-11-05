<?php
/**
 * Created by PhpStorm.
 * User: Jana
 * Date: 10. 5. 2018
 * Time: 21:55
 */

namespace Jss\Form;


class FormGroup extends FormContainer implements IFormComponent
{

    protected $name;
    protected $label;

    function __construct($name, $label)
    {
        $this->setName($name);
        $this->label = $label;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

}
