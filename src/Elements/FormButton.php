<?php

namespace Jss\Form\Elements;


class FormButton extends FormElement
{
    protected $content;

    public function __construct($name, $label = '', $content = '')
    {
        parent::__construct($name, $label);
        $this->elementType = 'button';
        $this->html_element_type = 'button';
        $this->content = $content;
    }

    /**
     * @return \Jss\Form\FormHtmlElement
     */
    public function getHtmlElement()
    {
        $element = parent::getHtmlElement();
        $element->addContent($this->content);
        return $element;
    }

    public function getContent()
    {
        return $this->content;
    }

}
