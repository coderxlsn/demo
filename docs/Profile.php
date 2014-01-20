<?php

class User_Form_Profile extends Zend_Form
{

    public function init()
    {
    	$this->setAttrib('enctype', 'multipart/form-data');
        /* Form Elements & Other Definitions Here ... */
    	$name = new Zend_Form_Element_Text('fullname');
    	$name->setAttrib('placeholder', 'Ваше имя')
        ->setAttrib('data-name', 'Имя');
    	$vk = new Zend_Form_Element_Text('vk');
    	$vk->setAttrib('placeholder', 'ВКонтакте')
    	->setAttrib('data-name', 'ВКонтакте');
    	$d1 = new Zend_Form_Element_Select('d1');
    	$d1->setAttrib('class', 'form-control');
    	for ($i=1;$i< 32;$i++)
    	{
    		$d1->addMultiOption($i,$i);
    	}
    	$d2 = new Zend_Form_Element_Select('d2');
    	$d2->setAttrib('class', 'form-control');
    	$array = array(
    			'Январь',
    			'Февраль',
    			'Март',
    			'Апрель',
    			'Май',
    			'Июнь',
    			'Июль',
    			'Август',
    			'Сентябрь',
    			'Октябрь',
    			'Ноябрь',
    			'Декабрь',
    	);
    	foreach ($array as $key=>$value)
    	{
    		$d2->addMultiOption($key,$value);
    	}
    	$d3 = new Zend_Form_Element_Select('d3');
    	$d3->setAttrib('class', 'form-control');
    	for ($i=2012;$i> 1950;$i--)
    	{
    		$d3->addMultiOption($i,$i);
    	}
    	$state = new Zend_Form_Element_Select('state');
    	
    	$state->setAttrib('class', 'form-control');
    	$array = array('Мужской','Женский');
    	foreach ($array as $key=>$value)
    	{
    		$state->addMultiOption($key,$value);
    	}
    	$file = new Zend_Form_Element_File('avatar_');
    	$file->setValueDisabled(true)
            ->addValidator('Count',false,1)
                  ->addValidator('Size',false,2097152)
                  ->setMaxFileSize(2097152)
                  ->addValidator('Extension',false,'jpg,jpeg,gif,png,bmp');
    	$this->addElements(array(
    		$name,
    		$vk,
    		$d1,
    		$d2,
    		$d3,
    		$state,	
    		$file,
    		
    	));
    	$this->setDecorators(array(array('ViewScript',array('viewScript' => 'form/profileform.phtml'), 'Form')));
    }


}

