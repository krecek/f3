<?php


namespace Jss\Form;


trait FormHtmlContent
{
    protected $content = [];

    public function getContent(): array
    {
        return $this->content;
    }

    abstract public function getHtml(): string;
}
