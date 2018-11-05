<?php
namespace Jss\Form\Elements;

class FormInputDate extends FormElementInput
{
    protected $type = 'text';

    public function __construct($name, $label, $id, $value = null)
    {
        if ($value) $value = date('j.n.Y', strtotime($value));
        parent::__construct($name, $label, $value);
        $this->elementType = 'date';
        $this->setAttribute('id', $id);

    }

    public function setDefault($value)
    {
        if ($value == '0000-00-00') return;
        if ($value) $value = date('j.n.Y', strtotime($value));
        parent::setDefault($value);
    }

    public function render()
    {
        $s = '';
        $s .= "\t<div class='" . join(' ', $this->wrapper_classes) . "'>\n";
        $s .= "\t\t<label>$this->label</label>";
        $s .= "<div class='input-group'>";
        $s .= "<div class='input-group-addon'>";
        $s .= "<i class='fa fa-calendar'></i>";
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
        if ($this->isRequired()) $s .= " required ";
        $s .= "/>\n";
        $s .= "</div>\n";
        if ($this->span) $s .= "\t\t<span class='$this->span'></span>\n";
        if ($this->error) $s .= "\t\t<span class='help-block with-errors'>$this->error</span>\n";
        $s .= "</div>\n";
        return $s;
    }
}

?>
