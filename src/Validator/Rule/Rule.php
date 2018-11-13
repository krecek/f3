<?php

namespace Jss\Form\Validator\Rule;
class Rule implements IRule
{
    protected $parameters = [];
    protected $errorMessage;
    protected $callback;

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
        return call_user_func_array($this->callback,array_merge($value,$this->parameters));
    }

    public function setParameters(array $parameters=array())
    {
        $this->parameters = $parameters;
    }

    public function getErrorMessage()
    {
        return vprintf($this->errorMessage,$this->parameters);
    }

}