<?php

namespace Jss\Form\Elements;


use Jss\Form\FormHtmlElement;
use Jss\Validator\Rule\IRule;
use Jss\Validator\Validator;

abstract class FormElement implements IFormElement
{

    public $name;
    public $value;
    public $default;
    public $attributes = array();
    /** @var string @var FormHtmlElement */
    public $label;
    protected $label_view = true;
    public $error;
    public $classes = array();
    public $classes_removed = array();
    public $wrapper_classes = array();
    public $wrapper_classes_removed = array();
    public $required = false;
    protected $elementType;
    protected $html_element_type;
    /** @var  @var FormHtmlElement */
    protected $html_element;
    public $span;
    protected $sendValue;
    protected $rules = array();

    public function __construct($name, $label = '', $value = '')
    {
        $this->setName($name);
        $this->value = $value;
        $this->label = $this->createLabel($label);
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }


    public function setSendValue($value)
    {
        $this->sendValue = $value;
    }

    public function getValue()
    {
        return trim($this->sendValue);
    }

    public function createLabel($label)
    {
        return new FormHtmlElement('label', [], $label);
    }

    public function getHtmlElementType()
    {
        return $this->html_element_type;
    }

    public function getHtmlElement()
    {
        $element = new FormHtmlElement($this->html_element_type);
        foreach($this->attributes as $attribute => $val) $element->setAttribute($attribute, $val);
        if($this->isRequired()) $element->setUnpairedAttribute('required');
        foreach($this->classes as $class) $element->addClass($class);
        return $element;
    }

    public function __toString()
    {
        return $this->getHtmlElement()->getHtml();
    }

    public function getElementType()
    {
        return $this->elementType;
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->setAttribute('name', $name);
    }

    public function setDefault($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setError($error)
    {
        $this->error = $error;
        $this->addWrapperClass('has-error');
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function hasError()
    {
        return (bool)$this->getError();
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    /**
     * View label?
     */
    public function getLabelView()
    {
        return $this->label_view;
    }

    public function removeLabel()
    {
        $this->label_view = false;
        return $this;
    }

    public function addClass($class)
    {
        $this->classes[$class] = $class;
        if(isset($this->classes_removed[$class])) unset($this->classes_removed[$class]);
        return $this;
    }

    public function removeClass($class)
    {
        if(isset($this->classes[$class])) unset($this->classes[$class]);
        $this->classes_removed[$class] = $class;
        return $this;
    }

    public function addWrapperClass($class)
    {
        $this->wrapper_classes[$class] = $class;
        if(isset($this->wrapper_classes_removed[$class])) unset($this->wrapper_classes_removed[$class]);
        return $this;
    }

    public function removeWrapperClass($class)
    {
        if(isset($this->wrapper_classes[$class])) unset($this->wrapper_classes[$class]);
        $this->wrapper_classes_removed[$class] = $class;
        return $this;
    }

    public function getWrapperClasses()
    {
        return $this->wrapper_classes;
    }

    public function setRequired()
    {
        $this->addWrapperClass('required');
        $this->required = true;
        return $this;
    }

    public function isRequired()
    {
        return $this->required;
    }

    protected function str2bool($str)
    {
        if($str === true || in_array($str, ['A', 'Y', 'ANO', 'on', '1'], true)) return true;
        else return false;
    }

    public function addRule($rule, $message=null, $parameter=null)
    {
        $r = new \Rule($rule);
        $r->setErrorMessage($message);
        if(!is_array($parameter)) $parameter = [$parameter];
        $r->setParameters($parameter);
        $this->rules[] = $r;
    }

    public function validate()
    {
        foreach($this->rules as $rule)
        {
            if(!$rule->validate($this->sendValue)) $this->setError($rule->getErrorMessage());
        }
    }

}
