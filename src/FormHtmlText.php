<?php

namespace Jss\Form;


class FormHtmlText
{
    use FormHtmlContent;

    function __construct($text)
    {
        $this->content[0] = $text;
    }

    public function getHtml(): string
    {
        return $this->content[0];
    }
}
