<?php
/**
 * Created by PhpStorm.
 * User: Jana
 * Date: 11. 5. 2018
 * Time: 20:54
 */

namespace Jss\Form\Renderer;


use Jss\Form\Elements\FormElement;
use Jss\Form\Form;
use Jss\Form\FormGroup;
use Jss\Form\FormHtmlElement;
use Jss\Form\IFormComponent;

class BootstrapRenderer implements IFormRenderer
{
    /**@var \Jss\Form\Form */
    protected $form;


    /**
     * @param Form $form
     * @return string
     */
    public function render(Form $form)
    {
        $this->form = $form;
        $s = '';
        $s .= "<form action='" . $form->getAction() . "' method='" . $form->getMethod() . "'";
        if ($form->getId()) $s .= " id='" . $form->getId() . "'";
        if ($form->getClasses()) $s .= " class='" . join(" ", $form->getClasses()) . "'";
        if ($form->getAttributes())
        {
            foreach ($form->getAttributes() as $key => $value)
            {
                $s .= " $key='$value'";
            }
        }
        $s .= ">\n";
        foreach ($form->getElements() as $component)
        {
            $s .= "\t" . $this->componentRender($component, $form->getInline());
            $s .= "\n";
        }
        $s .= "</form>";
        return $s;

    }

    protected function componentRender(IFormComponent $component, $inline)
    {
        $s = "";
        if (is_a($component, 'Jss\Form\Elements\IFormElement')) $s = $this->elementRender($component, $inline);
        elseif (is_a($component, 'Jss\Form\FormGroup')) $s = $this->groupRender($component, $inline);
        return $s;
    }

    protected function elementRender(FormElement $element, $inline = false)
    {
        $s = "";
        switch ($element->getElementType())
        {
            case "button":
                $control = $element->getHtmlElement();
                $control->addClass('btn');
                $s .= $control->getHtml();
                break;
            case "select":
                $s .= $this->renderSelectElement($element, $inline);
                break;
            case "suggestion":
                $element->addClass('form-control');
                $s .= $this->renderSelectElement($element, $inline);
                break;
            case "textarea":
                $element->addWrapperClass('form-group');
                $element->addClass('form-control');
                $wrapper = new FormHtmlElement('div');
                foreach ($element->getWrapperClasses() as $class) $wrapper->addClass($class);
                $wrapper->addContent("\t\t");
                $wrapper->addContent($element->getLabel());
                $wrapper->addContent($element->getHtmlElement());
                if ($element->hasError()) $wrapper->addContent(new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError()));
                $s .= $wrapper->getHtml();
                break;
            case "text":
                $element->addClass('form-control');
                $s .= $this->renderInputElement($element);
                break;
            case "file":
                $s .= $this->renderInputElement($element);
                break;
            case "date":
                $element->addClass('datepicker');
                $wrapper = $this->renderDateTimeElement($element, 'fa-calendar');
                $s .= $wrapper->getHtml();
                break;
            case "datetime":
                $wrapper = $this->renderDateTimeElement($element, 'fa-clock-o');
                $s .= $wrapper->getHtml();
                break;
            case "hidden":
                $s .= $element->getHtmlElement()->getHtml();
                break;
            case "submit":
                $element->removeClass('form-control');
                $element->addClass('btn')->addClass('btn-primary');
                $s .= $element->getHtmlElement()->getHtml();
                break;
            case "radio":
                $control = $element->getHtmlElement();
                $control->addClass('radio');
                $wrapper = new FormHtmlElement('div');
                foreach ($element->getWrapperClasses() as $class) $wrapper->addClass($class);
                $wrapper->addContent($element->getLabel());
                $wrapper->addContent($control);
                if ($element->hasError()) $wrapper->addContent(new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError()));
                $s .= $wrapper->getHtml();
                break;
            case "checkbox":
                $wrapper = new FormHtmlElement('div', ['checkbox']);
                $label = new FormHtmlElement('label', [], $element->getHtmlElement());
                $label->addContent($element->getLabel());
                $wrapper->addContent($label);
                $s .= $wrapper->getHtml();
                break;
            default:
                break;
        }
        return $s;
    }

    protected function groupRender(FormGroup $group, $inline = false)
    {
        $s = "<fieldset>
                <legend>
                    " . $group->getLabel() . "
                </legend>
                ";
        foreach ($group->getElements() as $component)
        {
            $s .= "\t" . $this->componentRender($component, $inline);
            $s .= "\n";
        }
        $s .= "</fieldset>";
        return $s;
    }

    /**
     * @param FormElement $element
     * @param $wrapper
     */
    protected function renderElementError(FormElement $element, FormHtmlElement $wrapper)
    {
        if ($element->hasError()) $wrapper->addContent(new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError()));
    }

    /**
     * @param FormElement $element
     * @param $wrapper
     */
    protected function renderElementSpan(FormElement $element,FormHtmlElement $wrapper)
    {
        if ($element->span) $wrapper->addContent(new FormHtmlElement('span', [$element->span]));
    }

    /**
     * @param FormElement $element
     * @param $icon_class
     * @return FormHtmlElement
     */
    protected function renderDateTimeElement(FormElement $element, $icon_class): FormHtmlElement
    {
        $element->addClass('pull-right');
        $wrapper = new FormHtmlElement('div');
        foreach ($element->getWrapperClasses() as $class) $wrapper->addClass($class);
        $wrapper->addContent($element->getLabel());
        $icon = new FormHtmlElement('div', ['input-group-addon'], new FormHtmlElement('i', ['fa', $icon_class]));
        $wrapper->addContent(new FormHtmlElement('div', ['input-group'], $icon));
        $wrapper->addContent($element->getHtmlElement());
        $this->renderElementSpan($element, $wrapper);
        $this->renderElementError($element, $wrapper);
        return $wrapper;
    }

    /**
     * @param FormElement $element
     * @param $inline
     * @return string
     */
    protected function renderSelectElement(FormElement $element, $inline): string
    {
        $s = "";
        $label = $element->getLabel();
        if (!$inline)
        {
            $wrapper = new FormHtmlElement('div');
            foreach ($element->getWrapperClasses() as $class) $wrapper->addClass($class);
            $wrapper->addContent($label);
            $wrapper->addContent($element->getHtmlElement());
            if ($element->hasError()) $wrapper->addContent(new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError()));
            $s .= $wrapper->getHtml();
        }
        else
        {
            $s .= $label->getHtml();
            $s .= $element->getHtmlElement()->getHtml();
            if ($element->hasError())
            {
                $span = new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError());
                $s .= $span->getHtml();
            }
        }
        return $s;
    }

    /**
     * @param FormElement $element
     * @return string
     */
    protected function renderInputElement(FormElement $element): string
    {
        $s = "";
        $wrapper = new FormHtmlElement('div', ['form-group']);
        foreach ($element->getWrapperClasses() as $class) $wrapper->addClass($class);
        if ($element->getLabelView()) $wrapper->addContent($element->getLabel());
        $wrapper->addContent($element->getHtmlElement());
        if ($element->span) $wrapper->addContent(new FormHtmlElement('span', [$element->span]));
        if ($element->hasError()) $wrapper->addContent(new FormHtmlElement('span', ['help-block', 'with-errors'], $element->getError()));
        $s .= $wrapper->getHtml();
        return $s;
    }
}
