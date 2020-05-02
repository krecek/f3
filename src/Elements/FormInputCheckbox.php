<?php

namespace Jss\Form\Elements;

class FormInputCheckbox extends FormElementInput
{

    protected $type = 'checkbox';

    public function __construct($name, $label = '', $value = '', $default_value = 'on')
    {
        parent::__construct($name, $label, $default_value);
        $this->elementType = 'checkbox';
        if ($this->str2bool($value) || ($value && $value == $default_value)) $this->setAttribute('checked', 'checked');
        $this->removeWrapperClass('form-control');
        $this->addWrapperClass('checkbox');
        $this->removeClass('form-control');
        $this->addClass('checkbox');
    }

    public function setDefault($value)
    {
        if ($this->str2bool($value) || ($value && $value == $this->value)) $this->setAttribute('checked', 'checked');
    }

    public function render()
    {
        $s = '';
        $s .= "<div class='checkbox'>";
        $s .= "<label>";
        $s .= "<input type='$this->type' name='$this->name' class='" . join(' ', $this->classes) . "'";
        if ($this->value) $s .= " value='$this->value'";
        if ($this->attributes)
        {
            foreach ($this->attributes as $key => $value)
            {
                $s .= " $key='$value'";
            }
        }
        $s .= "/>";
        $s .= "$this->label</label>\n";
        $s .= "</div>\n";
        return $s;
    }

}
