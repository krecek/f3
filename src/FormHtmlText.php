<?php

namespace Jss\Form;


class FormHtmlText
{
    use FormHtmlContent;

    function __construct($text)
    {
        $this->elementContent[0] = $text;
    }

    public function getHtml()
    {
        return $this->elementContent[0];
    }
}
