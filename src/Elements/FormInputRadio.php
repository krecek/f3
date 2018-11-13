<?php
namespace Jss\Form\Elements;

use Jss\Form\FormHtmlElement;

class FormInputRadio extends FormElement
{

    protected $type = 'radio';
    protected $value=[];
    protected $selected;

    public function __construct($name, $label = '', $values = '', $selected = '')
    {
        parent::__construct($name, $label, $values);
        $this->selected = $selected;
        $this->elementType = 'radio';
        $this->html_element_type = 'input';
        $this->addWrapperClass('form-group');
    }

    public function setDefault($value)
    {
        $this->selected = $value;
        return $this;
    }

    /**
     * @return \Jss\Form\FormHtmlElement
     */
    public function getHtmlElement()
    {
        $options = [];
        foreach ($this->value as $key => $text) $options[] = $this->createOption($key, $text, $this->selected == $key);
        $element = new FormHtmlElement('div');
        return $element;
    }

    public function createOption($value, $text, $checked = false)
    {
        $label = new FormHtmlElement('label', $this->classes);
        $control = new FormHtmlElement($this->html_element_type);
        $control->setAttribute('name', $this->name);
        $control->setAttribute('value', $value);
        if ($checked) $control->setUnpairedAttribute('checked');
        $label->addContent($control);
        $label->addContent($text);
        return $label;
    }

    public function render()
    {
        $s = '';
        $s .= "\t<div class='" . join(' ', $this->wrapper_classes) . "'>\n";
        $s .= "\t\t<label>$this->label</label>";
        $s .= "<div class='radio'>";
        foreach ($this->value as $key => $text)
        {
            $s .= "<label class='" . join(' ', $this->classes) . "'>";
            $s .= "<input type='$this->type' name='$this->name' value='$key'" . ($this->selected == $key ? "checked='checked'" : "") . "> $text";
            $s .= "</label>";
        }
        $s .= "</div>";
        $s .= "</div>\n";
        return $s;
    }

}


