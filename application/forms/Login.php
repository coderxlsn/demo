<?php

class Application_Form_Login extends Twitter_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	$email = new Zend_Form_Element_Text('email');
    	$email->addValidator(new Zend_Validate_EmailAddress())
    	->addFilter(new Zend_Filter_StringTrim())
    	->setAttrib('placeholder', 'Ваш e-mail')
    	->setLabel('Email')
    	->setRequired('true');
    	$frmPassword1=new Zend_Form_Element_Password('regpassword');
    	$frmPassword1->setLabel('Пароль')
    	->setRequired('true')
    	->addFilter(new Zend_Filter_StringTrim())
    	->addValidator(new Zend_Validate_NotEmpty())
    	->setAttrib('placeholder', 'Пароль');
    	$submit = new Zend_Form_Element_Submit('save');
    	$submit->setLabel('Зарегестрироватся');
    	$this->addElements(array(
    			$email,
    			$frmPassword1,
    			
    			$submit,
    			 
    	));
    }


}

