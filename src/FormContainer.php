<?php


namespace Jss\Form;


use Jss\Form\Elements\FormButton;

class FormContainer implements \ArrayAccess
{
    protected $elements = array();
    protected $allElements = array();
    protected $counter = 0;
    protected $hasFile = false;
    protected $validates=array();
    protected $errors=array();
    protected $submits = [];

    /**
     * @param string
     * @return bool|Elements\IFormElement
     */
    public function getElement($name)
    {
        if(isset($this->elements[$name])) return $this->elements[$name];
        else return false;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function getAllElements()
    {
        return $this->allElements;
    }

    //<editor-fold desc="Elements">
    public function addButton($name, $label = '', $content='')
    {
        return $this->addElement(new FormButton($name, $label, $content));
    }

    public function addText($name, $label = '', $value = '', $placeholder = '')
    {
        return $this->addElement(new Elements\FormInputText($name, $label, $value, $placeholder));
    }

    public function addHidden($name, $value = '')
    {
        return $this->addElement(new Elements\FormInputHidden($name, $value));
    }

    public function addSubmit($name, $value = '')
    {
        $this->submits[$name] = $value;
        return $this->addElement(new Elements\FormInputSubmit($name, '', $value));
    }

    public function addCheckbox($name, $label, $value = false)
    {
        return $this->addElement(new Elements\FormInputCheckbox($name, $label, $value));
    }

    public function addMultipleCheckbox($name, array $values, $selected = '')
    {
        $last = null;
        foreach($values as $key => $value)
        {
            $last = $this->addElement(new Elements\FormInputCheckbox($name . "[$key]", $value));
        }
        return $last;
    }

    public function addPassword($name, $label = '', $placeholder = '')
    {
        return $this->addElement(new Elements\FormInputPassword($name, $label, $placeholder));
    }

    public function addFile($name, $label = '', $value = '')
    {
        $this->afterAddFile();
        return $this->addElement(new Elements\FormInputFile($name, $label, $value));
    }

    public function addTextarea($name, $label, $value = '')
    {
        return $this->addElement(new Elements\FormTextArea($name, $label, $value));
    }

    public function addSelect($name, $label, array $values = [], $selected = '')
    {
        return $this->addElement(new Elements\FormSelect($name, $label, $values, $selected));
    }

    public function addMultiselect($name, $label, array $values, $selected = '')
    {
        return $this->addElement(new Elements\FormMultiSelect($name, $label, $values, $selected));
    }

    public function addRadio($name, $label, array $values, $selected = '')
    {
        return $this->addElement(new Elements\FormInputRadio($name, $label, $values, $selected));
    }

    public function addDateTime($name, $label, $id, $values = null)
    {
        return $this->addElement(new Elements\FormInputDateTime($name, $label, $id, $values));
    }

    public function addDate($name, $label, $id, $values = null)
    {
        return $this->addElement(new Elements\FormInputDate($name, $label, $id, $values));
    }

    public function addTime($name, $label, $id, $values = null)
    {
        return $this->addElement(new Elements\FormInputTime($name, $label, $id, $values));
    }

    public function addSuggestion($name, $identification, $label = '', $placeholder = '', $selected = '', $width = '100%')
    {
        return $this->addElement(new Elements\FormSuggestion($name, $identification, $label, $placeholder, $selected, $width));
    }

    //</editor-fold>

    protected function addComponent(IFormComponent $component)
    {
        $name = $component->getName();
        $this->elements[$name] = $component;
        return $this->elements[$name];
    }

    protected function addElement(Elements\IFormElement $element)
    {
        $this->allElements[$element->getName()] = $element;
        return $this->addComponent($element);
    }

    public function addGroup(FormGroup $group)
    {
        if($group->hasFile()) $this->afterAddFile();
        $this->allElements = array_merge($this->allElements, $group->getAllElements());
        return $this->addComponent($group);
    }

    protected function afterAddFile()
    {
        $this->hasFile = true;
    }

    public function removeComponent(IFormComponent $component)
    {
        $name = $component->getName();
        unset($this->elements[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return (bool)$this->getElement($name);
    }

    /**
     * @param string $name
     * @return Elements\IFormElement
     */
    public function offsetGet($name)
    {
        return $this->getElement($name);
    }

    /**
     * @param mixed $name
     * @param IFormComponent $component
     */
    public function offsetSet($name, $component)
    {
        $component->setName($name);
        $this->addComponent($component);
    }

    /**
     * @param string $name
     */
    public function offsetUnset($name)
    {
        $this->removeComponent($this->getElement($name));
    }

    public function hasFile()
    {
        return $this->hasFile;
    }

    public function getValues()
    {
        $values = [];
        foreach($this->getAllElements() as $element) $values[$element->getName()] = $element->getValue();
        return $values;
    }


    public function setValues($values = null)
    {
        if(!$values) return;
        foreach($values as $key => $value)
        {
            if(isset($this->allElements[$key])) $this->allElements[$key]->setSendValue($value);
        }
    }

    public function addError(string $message)
    {
        $this->errors[]=$message;
    }

    public function getErrors()
    {
        foreach ($this->getAllElements() as $element)
        {
            if ($element->getError()) $this->errors[$element->getName()] = $element->getError();
        }
        return $this->errors;
    }

    public function hasError()
    {
        return (bool)$this->getErrors();
    }
}

