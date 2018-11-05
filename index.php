<?php
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

function dd($var, $title)
{
    echo $title . '<pre>';
    var_dump($var);
    echo '</pre>';
}
include_once 'src/autoload.php';
//$textInput=new \Jss\Form\FormHtmlElement('input',['a','b'],'text');
//dd($textInput->getHtml(), 'input');
//echo $textInput->getHtml();


//$form = new Form('','get');
//$form->addTextarea('a','b');

$form = new Form('', 'get');
$form->addSelect('a','b',['C'=>'d', 'E'=>'f'])->setPrompt('--aa--');
$form->setDefaults(['a'=>'C']);
//dd($form, 'form');
$s = $form->render();
echo $s;
