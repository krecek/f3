<?php
namespace Jss\Form\Elements;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FormInputTime extends FormElementInput
{
    protected $type = 'text';

    public function __construct($name, $label = '', $id='time', $value = null)
    {
        if ($value) $value = date('H:i', strtotime($value));
        parent::__construct($name, $label, $value);
        $this->setAttribute('id', $id);
        $this->elementType = 'datetime';


    }

    public function setDefault($value)
    {
        $value = date('H:i', strtotime($value));
        parent::setDefault($value);
    }

    public function render()
    {
        $s = '';
        $s .= "\t<div class='" . join(' ', $this->wrapper_classes) . "'>\n";
        $s .= "\t\t<label>$this->label</label>";
        $s .= "<div class='input-group'>";
        $s .= "<div class='input-group-addon'>";
        $s .= "<i class='fa fa-clock-o'></i>";
        $s .= "</div>";
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
        $s .= "</div>\n";
        $s .= "</div>\n";
        return $s;
    }
}


