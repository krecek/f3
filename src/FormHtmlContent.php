<?php


namespace Jss\Form;


trait FormHtmlContent
{
    protected $content = [];

    public function getContent()
    {
        return $this->content;
    }

    abstract public function getHtml();
}
