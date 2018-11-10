<?php
session_start();
/**
 * @package     FORMS
 * @description generování formulářů
 * @author      Jana Seňková
 *
 */


//$form = new Form('odkaz');
//$form->addText('popis', 'test popisu', 'hodnota', 'placeholder')->setVlastnost('id', 'popis_id');
//$form->addHidden('skryte_pole', 'hodnota_skryteho_pole');
//$form->addCheckbox('zaskrtavatkoi', 'Check box 1', true);
//$form->addMultipleCheckbox('pole', array('1'=>true, '2'=>false));
//$form->addPassword('passwd', 'Zadejte heslo:');
//$form->addArea('area', 'text', 'test area');
//$form->addSelect('select', 'Vyberte:', array('1'=>'Možnost 1', '2'=>'Možnost 2'), '2');
//$form->addFile('soubor', 'Zvolte soubor:');
//$form->addDateTime('datum','Zvolte datum a čas','id'); //id se použije pro napárování js
//$form->setDefaults(array('popis'=>'Text popisu', 'area'=>'Nějaký text)); //nastavení výchozích hodnot
//$form->addSubmit('send', 'Uložit');
//echo $form;

//$form = new Form('action="a.php"', 'GET', 'predem_urcene_id_pro_js');
//$form->setInline(true); // formulář se vypíše do jednoho řádku

//na všechny formulářová prvky lze zavolat metody:
//$form['area']->setDefault('výchozí hodnota') - nastavit výchozí hodnotu
//$form['area']->setError('chybová hláška') - nastavit chybovou hlášku
//$form['area']->setVlastnost('cols','12') - nastavit vlastnost html prvku, např. délka, výška, css style apod.
//$form['area']->getName - vrátí název (identifikátor prvku) - v tomto případě 'area'
//$form['area']->addClass('hidden') - přidá class
//$form['area']->removeClass('hidden') - odebere class
//$form['area']->addWrapperClass('form-element') - přidá class nadřazenému prvku
//$form['area']->removeWrapperClass('form-element') - odebere class nadřazenému prvku
//$form['area']->setRequired() - nastaví jako povinný (pomocí class='required' a vlastnoti html required)
//$form['area']->isRequired() - vrací true/false


use Jss\Form\Form;
use Jss\Form\FormGroup;

function dd($var, $title = '')
{
    echo $title . '<pre>';
    var_dump($var);
    echo '</pre>';
}

function overit(\Jss\Form\FormContainer $form)
{
    $values = $form->getValues();

    if ($values['a'] != 'W')
    {
        $form->addError('A musí být "W"');
//        $form['a']->setError('A musí být X');
    }
    if ($values['b'] != 'C') $form->addError('B musí být "C"');
    return !$form->hasError();
}

include_once 'src/autoload.php';

$form = createForm();
$form2 = createForm2();
if ($form->isSubmitted())
{
    if (!$form->validate())
    {
        $form->saveState();
        header("Location: http://localhost/f3/index.php");
    }
}
elseif ($form2->isSubmitted())
{
    if (!$form2->validate())
    {
        $form2->saveState();
        header("Location: http://localhost/f3/index.php");
    }
}
else
{
    dd($form->getErrors(), 'chyby form1');
    dd($form2->getErrors(), 'chyby form2');
    echo $form->render();
    echo $form2;
}
die();

function createForm()
{
    $form = new Form('', 'post', 'form1');
    $form->addText('a', 'A:', 'A');
    $form->addSubmit('send', 'Uložit');
    $form->addSubmit('odeslano', 'Odeslat');
    $form->loadState();
    $form->addValidate('overit');
    return $form;
}

function createForm2()
{
    $form = new Form('', 'post', 'form2');
    $form->addText('b', 'B:', 'B');
    $form->addSubmit('send', 'Uložit');
    $form->loadState();
    $form->addValidate('overit');
    return $form;
}

