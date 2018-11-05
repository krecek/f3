<?php

namespace Jss\Form\Elements;

class FormInputFile extends FormElementInput
{
    protected $type = 'file';

    public function __construct($name, $label = '', $value = '')
    {
        parent::__construct($name, $label, $value);
        $this->elementType = 'file';
    }
}
