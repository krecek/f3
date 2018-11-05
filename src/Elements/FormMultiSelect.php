<?php

namespace Jss\Form\Elements;

class FormMultiSelect extends FormSelect
{

    protected $selected = array();

    public function __construct($name, $label = '', $values = '', $selected = '')
    {
        parent::__construct($name, $label, $values, $selected);
        $this->elementType = 'select';
        $this->setAttribute('multiple', '');
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->setAttribute('name', $name . '[]');
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isSelected($value): bool
    {
        return in_array($value, $this->selected);
    }

    public function render($inline = false)
    {
        $s = '';
        if (!$inline) $s .= "<div class='" . join(' ', $this->wrapper_classes) . "'>";
        $s .= "<label>$this->label</label>";
        $s .= "<select name='$this->name" . '[]' . "' class='" . join(' ', $this->classes) . "'";
        if ($this->attributes)
        {
            foreach ($this->attributes as $key => $value)
            {
                $s .= " $key='$value'";
            }
        }
        if ($this->isRequired()) $s .= " required ";
        $s .= ">\n";
        if ($this->prompt) $s .= "\t\t<option value=''>$this->prompt</option>\n";
        foreach ($this->value as $key => $text)
        {
            if (is_array($text))
            {
                $s .= "<optgroup label='$key'>";
                foreach ($text as $k => $v)
                {
                    $s .= "\t\t<option value='$k'" . (in_array($k, $this->selected) ? " selected='selected'" : "") . ">$v</option>\n";
                }
                $s .= "</optgroup>";
            }
            else  $s .= "\t\t<option value='$key'" . (in_array($key, $this->selected) ? " selected='selected'" : "") . ">$text</option>\n";
        }
        $s .= "\t</select>";
        if ($this->error) $s .= "\t\t<span class='help-block with-errors'>$this->error</span>\n";
        if (!$inline) $s .= "</div>";
        return $s;
    }
}
