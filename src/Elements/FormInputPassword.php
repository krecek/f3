<?php

namespace Jss\Form\Elements;

class FormInputPassword extends FormElementInput
{
    protected $type = 'password';

    public function __construct($name, $label = '', $placeholder = '')
    {
        parent::__construct($name, $label, '');
        $this->elementType = 'text';
        if ($placeholder) $this->setAttribute('placeholder', $placeholder);
    }
}
