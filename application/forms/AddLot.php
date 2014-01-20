<?php

class Application_Form_AddLot extends Twitter_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	$id = new Zend_Form_Element_Hidden('id');
    	$name = new Zend_Form_Element_Text('name');
    	$name->setLabel('Название товара');
    	$cost = new Zend_Form_Element_Text('cost');
    	$cost->setLabel('Цена')
    	->setValue('1.00')
    	//>setValue("1.00")
         ->addValidator('Regex',false, array('pattern' =>'/^\$?[0-9]+(,[0-9]{3})*(.[0-9]{2})?$/'));
    	$submit = new Zend_Form_Element_Submit('save');
    	$submit->setLabel('Сохранить');
    	$this->addElements(array(
    			$id,
    			$name,
    			$cost,
    			$submit
    	));
    }


}

