<?php

namespace Jss\Form\Validator\Rule;

use Jss\Form\Validator\FormValidator;

class Rule implements IRule
{
    protected $parameters = [];
    protected $errorMessage;
    protected $callback;
    /** @var FormValidator */
    protected $validator = FormValidator::class;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function validate($value)
    {
        return call_user_func_array($this->callback, array_merge([$value], $this->parameters));
    }

    public function setParameters(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    public function getErrorMessage()
    {
        if (!$this->errorMessage) $this->errorMessage = $this->getDefaultErrorMessage();
        return vsprintf($this->errorMessage, $this->parameters);
    }

    protected function getDefaultErrorMessage()
    {
        if (property_exists($this->validator, 'messages') && $this->validator::$messages[$this->callback]) return $this->validator::$messages[$this->callback];
        else return '';
    }

}