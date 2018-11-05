<?php

namespace Jss\Form\Elements;

class FormInputSubmit extends FormElementInput
{

    protected $type = 'submit';

    public function __construct($name, $label = '', $value = '')
    {
        parent::__construct($name, $label, $value);
        $this->elementType = 'submit';

    }

    public function render()
    {
        $s = '';
        $s .= "<input type='$this->type' name='$this->name' class='" . join(' ', $this->classes) . "'";
        if ($this->value) $s .= " value='$this->value'";
        if ($this->attributes)
        {
            foreach ($this->attributes as $key => $value)
            {
                $s .= " $key='$value'";
            }
        }
        $s .= "/>\n";

        return $s;
    }

}
