<?php

namespace Jss\Form\Elements;

class FormTextArea extends FormElement
{

    public function __construct($name, $label = '', $value = '')
    {
        parent::__construct($name, $label, $value);
        $this->elementType = 'textarea';
        $this->html_element_type='textarea';
    }

    /**
     * @return \Jss\Form\FormHtmlElement
     */
    public function getHtmlElement()
    {
        $element = parent::getHtmlElement();
        $element->addContent($this->value);
        return $element;
    }


    function render()
    {
        $s = '';
        $s .= "\t<div class='" . join(' ', $this->wrapper_classes) . "'>\n";
        $s .= "\t\t<label>$this->label</label>";
        $this->getHtmlElement();
        if ($this->error) $s .= "\t<span class='help-block'>$this->error</span>\n";
        $s .= "</div>\n";
        return $s;
    }

}
