<?php

namespace Jss\Form\Elements;

class FormInputHidden extends FormElementInput
{

    protected $type = 'hidden';

    public function __construct($name, $value = '')
    {
        parent::__construct($name, '', $value);
        $this->elementType = 'hidden';

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
