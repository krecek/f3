<?php

namespace Jss\Form\Elements;

abstract class FormElementInput extends FormElement
{

    protected $type = 'text';
    public $span = null;

    public function __construct($name, $label = '', $value = '')
    {
        parent::__construct($name, $label, $value);
        $this->elementType = 'input';
        $this->html_element_type='input';
    }

    /**
     * @return \Jss\Form\FormHtmlElement
     */
    public function getHtmlElement()
    {
        $element = parent::getHtmlElement();
        $element->setAttribute('value', htmlspecialchars($this->value));
        $element->setAttribute('type',$this->type);
        return $element;
    }



    public function render()
    {
        $s = '';
        $s .= "\t<div class='" . join(' ', $this->wrapper_classes) . "'>\n";
        if ($this->label_view) $s .= "\t\t<label>$this->label</label>";
        $s .= "<input type='$this->type' name='$this->name' class='" . join(' ', $this->classes) . "'";
        if ($this->value) $s .= " value='" . htmlspecialchars($this->value) . "'";
        if ($this->attributes)
        {
            foreach ($this->attributes as $key => $value)
            {
                $s .= " $key='$value'";
            }
        }
        if ($this->isRequired()) $s .= " required ";
        $s .= "/>\n";
        if ($this->span) $s .= "\t\t<span class='$this->span'></span>\n";
        if ($this->error) $s .= "\t\t<span class='help-block with-errors'>$this->error</span>\n";
        $s .= "</div>\n";
        return $s;
    }

    public function addSpan($span_class)
    {
        $this->span = $span_class;
        return $this;
    }


}
