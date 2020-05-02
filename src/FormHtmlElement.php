<?php

namespace Jss\Form;


class FormHtmlElement
{
    use FormHtmlContent;

    protected $classes = [];
    protected $type;
    protected $isPair = true;
//    protected $content = [];
    protected $attributes = [];
    protected $unpairedAttributes = [];
    protected $pairs = [
        'div', 'span', 'label', 'textarea', 'button', 'a', 'optgroup', 'i', 'option', 'select', 'p',
    ];
    protected $allowed = [
        'div', 'span', 'label', 'input', 'button', 'textarea', 'select', 'option', 'optgroup', 'i', 'p',
    ];
    protected $classes_removed;

    function __construct($type, array $classes = [], $content = null)
    {
        $this->type = $type;
        $this->isPair = $this->isPair($type);
        if ($content) $this->addContent($content);
        if ($classes)
        {
            foreach ($classes as $class) $this->addClass($class);
        }
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function setUnpairedAttribute($attribute)
    {
        $this->unpairedAttributes[$attribute] = $attribute;
        return $this;
    }

    public function addClass($class)
    {
        $this->classes[$class] = $class;
        if (isset($this->classes_removed[$class])) unset($this->classes_removed[$class]);
        return $this;
    }

    public function removeClass($class)
    {
        if (isset($this->classes[$class])) unset($this->classes[$class]);
        $this->classes_removed[$class] = $class;
        return $this;
    }

    public function addContent($content)
    {
//        if ($this->isPair)
//        {
            if (is_string($content))
            {
                $this->content[] = new FormHtmlText($content);
            }
            elseif (is_a($content, 'Jss\Form\FormHtmlElement') || is_a($content, 'Jss\Form\FormHtmlText')) $this->content[] = $content;
//        }
//        else
//        {
//            dd($content, 'content');
//        }
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    protected function isPair($type)
    {
        return in_array($type, $this->pairs);
    }

    public function getHtml()
    {
        $s = "";
        if ($this->isPair)
        {
            $s .= "\t<" . $this->getType() . " class='" . (join(' ', $this->classes)) . "' " . (join(' ', $this->unpairedAttributes));
            foreach ($this->attributes as $attribute => $val) $s .= " $attribute='$val'";
            $s .= ">\n";
            foreach ($this->getContent() as $content)
            {
                $s .= $content->getHtml() . "\n";
            }
            $s .= "\t</" . $this->getType() . ">\n";
        }
        else
        {
            $s .= "\t<" . $this->getType() . " class='" . (join(' ', $this->classes)) . "' " . (join(' ', $this->unpairedAttributes));
            foreach ($this->attributes as $attribute => $val) $s .= " $attribute='$val'";
            $s .= ">\n";
        }
        return $s;
    }


}
