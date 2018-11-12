<?php


class Rule
{
    protected $parameters = [];
    protected $errorMessage;
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
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