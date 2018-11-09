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
    protected $validates = array();


    public function __construct($action, $method = 'post', $id = '')
    {
        $this->hash = $this->createHash();
        $this->action = $action;
        $this->setMethod($method);
        $this->id = $id;
        return $this;
    }

    public function getValues()
    {
        $values = parent::getValues();
        if(isset($values['form_id'])) unset($values['form_id']);
        if(isset($values['send'])) unset($values['send']);
        return $values;
    }

    public function setMethod($method = 'post')
    {
        $method = strtolower($method);
        $methods = ['post', 'get'];
        if(!in_array($method, $methods)) $method = 'post';
        if($method=='post')  $this->addHash('form_id', $this->hash);
        elseif (isset($this['form_id'])) unset($this['form_id']);
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
        if(isset($this->classes[$class])) unset($this->classes[$class]);
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
        $this->setMethod('post');
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function setDefaults($values = null)
    {
        if(!$values) return;
        foreach($values as $key => $value)
        {
            if($key !== 'form_id' && isset($this->elements[$key]))
            {
                $this->elements[$key]->setDefault($value);
            }
        }
    }

    public function addValidate(callable $callback)
    {
        $this->validates[] = $callback;
    }

    public function validate()
    {
        foreach($this->getAllElements() as $element) $element->validate();
        foreach($this->validates as $validate) call_user_func($validate, $this);
        return !$this->hasError();
    }

    public function saveState()
    {
        $_SESSION['form_errors'] = $this->getErrors();
        $_SESSION['form_values'] = $this->getValues();
    }

    public function loadState()
    {
        if(isset($_SESSION['form_values']))
        {
            $this->setDefaults($_SESSION['form_values']);
            unset($_SESSION['form_values']);
        }
        if(isset($_SESSION['form_errors']))
        {
            $this->setErrors($_SESSION['form_errors']);
            unset($_SESSION['form_errors']);
        }
    }

    public function loadValues()
    {
        if($this->method == 'post') $this->setValues($_POST);
        elseif($this->method == 'get') $this->setValues($_GET);
    }

    public function setErrors($errors = null)
    {
        if(!$errors) return;
        foreach($errors as $key => $error)
        {
            if(isset($this->elements[$key]))
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
        if($this->renderer === NULL) $this->renderer = new BootstrapRenderer();
        return $this->renderer;
    }


}
