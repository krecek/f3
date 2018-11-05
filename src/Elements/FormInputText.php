<?php

namespace Jss\Form\Elements;
class FormInputText extends FormElementInput
{
    protected $type = 'text';

    public function __construct($name, $label = '', $value = '', $placeholder = '')
    {
        parent::__construct($name, $label, $value);
        if ($placeholder) $this->setAttribute('placeholder', $placeholder);
        $this->elementType = 'text';

    }


}
