<?php

namespace Jss\Form\Elements;


class FormSuggestion extends FormSelect
{
    public function __construct($name, $identification, $label = '', $placeholder = '', $selected = '', $width = '100%')
    {
        parent::__construct($name, $label, array($placeholder), $selected);
        $this->addClass($identification)->setAttribute('style', "width: $width");
        $this->elementType = 'suggestion';
    }
}

