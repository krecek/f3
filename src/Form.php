<?php

namespace Jss\Form;

use Jss\Form\Renderer\BootstrapRenderer;
use Jss\Form\Renderer\IFormRenderer;

class Form extends FormContainer
{

    private $method = 'post';
    private $id = null;
    private $action = '';
    private $attributes = array();
    private $inline = false;
    public $classes = array();
    private $hash = '';
    private $renderer = null;
    private $validates=array();


    public function __construct($action, $method = 'post', $id = '')
    {
        $this->action = $action;
        $this->method = $method;
        $this->id = $id;
        $this->hash = $this->createHash();
        $this->addHidden('form_id', $this->hash);
        return $this;
    }

    public function setMethod($method='post')
    {
        $this->method = $method;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setInline($inline = false)
    {
        $this->inline = $inline;
        $this->addClass('form-inline');
        return $this;
    }

    public function addClass($class)
    {
        $this->classes[$class] = $class;
        return $this;
    }

    public function removeClass($class)
    {
        if (isset($this->classes[$class])) unset($this->classes[$class]);
        return $this;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }


    public function getInline()
    {
        return $this->inline;
    }

    protected function afterAddFile()
    {
        parent::afterAddFile();
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->method = 'post';
    }


    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function setDefaults($values = null)
    {
        if (!$values) return;
        foreach ($values as $key => $value)
        {
            if ($key !== 'form_id' && isset($this->elements[$key]))
            {
                $this->elements[$key]->setDefault($value);
            }
        }
    }

    public function setErrors($errors = null)
    {
        if (!$errors) return;
        foreach ($errors as $key => $error)
        {
            if (isset($this->elements[$key]))
            {
                $this->elements[$key]->setError($error);
            }
        }
    }

    public function render()
    {
        $renderer = $this->getRenderer();
        $s = $renderer->render($this);
        return $s;
    }

    public function __toString()
    {
        return $this->render();
    }


    protected function createHash()
    {
        return md5(uniqid(rand(), true));
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setRenderer(IFormRenderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return null|BootstrapRenderer
     */
    public function getRenderer()
    {
        if ($this->renderer === NULL) $this->renderer = new BootstrapRenderer();
        return $this->renderer;
    }

    public function addValidate(callable $callback)
    {
        $this->validates[]=$callback;
    }

    public function validate()
    {
        foreach ($this->getElements() as $element) $element->validate();
        foreach($this->validates as $validate) call_user_func($validate, $this->getValues());
    }

    public function getValues()
    {
        $values=[];
        foreach($this->getElements() as $element) $values[$element->getName]=$element->getValue;
        return $values;
    }
}
