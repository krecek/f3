<?php

namespace Jss\Form\Elements;

use Jss\Form\FormHtmlElement;

class FormSelect extends FormElement
{

    protected $selected = '';
    protected $prompt = null;
    public $value=[];


    public function __construct($name, $label = '', $values = '', $selected = '')
    {
        foreach($values as $key => $value) $values[$key] = (string)$value;
        parent::__construct($name, $label, $values);
        $this->elementType = 'select';
        $this->html_element_type = 'select';
        $this->selected = $selected;
        $this->addClass('form-control');
        $this->addClass('form-group');
    }

    public function setDefault($value)
    {
        $this->selected = $value;
        return $this;
    }

    public function setPrompt($text)
    {
        $this->prompt = $text;
        return $this;
    }

    /**
     * @return \Jss\Form\FormHtmlElement
     */
    public function getHtmlElement()
    {
        $element = parent::getHtmlElement();
        $options = [];
        if ($this->prompt) $options[] = $this->createOption('', $this->prompt);
        foreach ($this->value as $key => $text)
        {
            if (!is_array($text)) $options[] = $this->createOption($key, $text, $this->isSelected($key));
            else
            {
                $optgroup = new FormHtmlElement('optgroup');
                foreach ($text as $k => $v)
                {
                    $optgroup->addContent("\t\t");
                    $optgroup->addContent($this->createOption($k, $v, $this->isSelected($k)));
                }
                $options[] = $optgroup;
            }
        }
        foreach ($options as $option) $element->addContent($option);
        return $element;
    }

    protected function createOption($value, $text, $selected = false)
    {
        $option = new FormHtmlElement('option', [], $text);
        $option->setAttribute('value', $value);
        if ($selected) $option->setUnpairedAttribute('selected');
        return $option;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isSelected($value)
    {
        return $this->selected == $value;
    }

    public function render($inline = false)
    {
        $s = '';
        if (!$inline) $s .= "<div class='" . join(' ', $this->wrapper_classes) . "'>";
        $s .= "<label>$this->label</label>";
        $s .= "<select name='$this->name' class='" . join(' ', $this->classes) . "'";
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
                foreach ($text as $k => $h)
                {
                    $s .= "\t\t<option value='$k'" . ($this->selected == $k ? " selected='selected'" : "") . ">$h</option>\n";
                }
                $s .= "</optgroup>";
            }

            else  $s .= "\t\t<option value='$key'" . ($this->selected == $key ? " selected='selected'" : "") . ">$text</option>\n";
        }
        $s .= "\t</select>";
        if ($this->error) $s .= "\t\t<span class='help-block with-errors'>$this->error</span>\n";
        if (!$inline) $s .= "</div>";
        return $s;
    }


}
