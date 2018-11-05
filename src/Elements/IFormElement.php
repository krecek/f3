<?php

namespace Jss\Form\Elements;

use Jss\Form\IFormComponent;

interface IFormElement extends IFormComponent
{

    public function setDefault($value);

    public function setAttribute($key, $value);

    public function setError($error);

    public function setRequired();
}
