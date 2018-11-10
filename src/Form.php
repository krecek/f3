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
    protected $loaded = false;
    protected $checkValidate = false;


    const FORM_HASH_NAME = 'form_id';

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
        if (!$this->loaded) $this->loadValues();
        $values = parent::getValues();
        if (isset($values[self::FORM_HASH_NAME])) unset($values[self::FORM_HASH_NAME]);
        foreach ($this->submits as $submit => $val)
        {
            if (isset($values[$submit])) unset($values[$submit]);
        }
        return $values;
    }

    public function submittedBy()
    {
        if (!$this->loaded) $this->loadValues();
        $values = parent::getValues();
        foreach ($this->submits as $submit => $val)
        {
            if (isset($values[$submit]) && $values[$submit] == $val) return $submit;
        }
        return false;
    }

    public function isSubmitted()
    {
        return (bool)$this->submittedBy();
    }

    public function isSuccess()
    {
        return ($this->isSubmitted() && $this->validate());
    }

    public function setMethod($method = 'post')
    {
        $method = strtolower($method);
        $methods = ['post', 'get'];
        if (!in_array($method, $methods)) $method = 'post';
        if ($method == 'post') $this->addHash();
        elseif (isset($this[self::FORM_HASH_NAME])) unset($this[self::FORM_HASH_NAME]);
        $this->method = $method;
    }

    protected function addHash()
    {
        $_SESSION['form_' . $this->hash] = time();
        return $this->addElement(new Elements\FormInputHidden('' . self::FORM_HASH_NAME . '', $this->hash));
    }

    protected function validateHash()
    {
        if ($this->getMethod() != 'post') return true;
        $hash = $this[self::FORM_HASH_NAME]->getValue();
        if (!isset($_SESSION["form_$hash"]))
        {
            $this->addError('Selhala kontrola formuláře');
            return false;
        }
        unset($_SESSION["form_$hash"]);
        foreach ($_SESSION as $key => $time) if (preg_match('~^form_~', $key) && strtotime('-15 minutes') > $time) unset($_SESSION[$key]);
        return true;
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
        $this->setMethod('post');
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
            if ($key !== self::FORM_HASH_NAME && isset($this->elements[$key]))
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
        if (!$this->checkValidate)
        {
            $this->validateHash();
            foreach ($this->getAllElements() as $element)
            {
                $element->validate();
            }
            foreach ($this->validates as $validate) call_user_func($validate, $this);
            $this->checkValidate = true;
        }
        return !$this->hasError();
    }

    public function saveState()
    {
        $_SESSION['form_errors'] = $this->getErrors();
        $_SESSION['form_values'] = $this->getValues();
    }

    /**
     * Obnoví uložený stav formuláře (stav uložit funkcí saveState()
     */
    public function loadState()
    {
        if (isset($_SESSION['form_values']))
        {
            $this->setDefaults($_SESSION['form_values']);
            unset($_SESSION['form_values']);
        }
        if (isset($_SESSION['form_errors']))
        {
            dd($_SESSION['form_errors'], 'SS');
            $this->setErrors($_SESSION['form_errors']);
            unset($_SESSION['form_errors']);
        }
    }

    /**
     * Do formuláře načte data odeslaná data
     */
    public function loadValues()
    {
        if (!$this->loaded)
        {
            if ($this->method == 'post') $this->setValues($_POST);
            elseif ($this->method == 'get') $this->setValues($_GET);
            $this->loaded = true;
        }
    }

    /**
     *
     * @param array|null $errors
     */
    public function setErrors(array $errors = null)
    {
        if (!$errors) return;
        foreach ($errors as $key => $error)
        {
            if (isset($this->allElements[$key]))
            {
                $this->allElements[$key]->setError($error);
            }
            else $this->addError($error);
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


}
