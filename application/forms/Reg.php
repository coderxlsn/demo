<?php

class Application_Form_Reg extends Twitter_Form
{

    public function init()
    {
    	$this->setAttrib('class',"form-horizontal");
    	$email = new Zend_Form_Element_Text('regmail');
    	$email->addValidator(new Zend_Validate_EmailAddress())
    	->addFilter(new Zend_Filter_StringTrim())
    	->setAttrib('placeholder', 'Ваш e-mail')
    	->setLabel('Email')
    	->setRequired('true')
    	->addValidator(new Zend_Validate_Db_NoRecordExists(
    			array(
    					'table'   => 'user',
    					'field'   => 'email',
    					 
    			)
    	),false)
    	->setAttrib('autocomplete', 'off')
    	;
        /* Form Elements & Other Definitions Here ... */
    	$frmPassword1=new Zend_Form_Element_Password('regpassword');
    	$frmPassword1->setLabel('Пароль')
    	->setRequired('true')
    	->addFilter(new Zend_Filter_StringTrim())
    	->addValidator(new Zend_Validate_NotEmpty())
    	->setAttrib('placeholder', 'Пароль')

    	->addValidator(new Zend_Validate_Regex(array('pattern' => '/^[a-z0-9]+$/')))
    	->addValidator(new Zend_Validate_StringLength(6, 16), true)
    	->setAttrib('autocomplete', 'off');
    	
    	$frmPassword2=new Zend_Form_Element_Password('confirm_password');
    	$frmPassword2->setLabel('Подтвердите пароль')
    	->setRequired('true')
    	->addFilter(new Zend_Filter_StringTrim())
    	->addValidator(new Zend_Validate_Identical('regpassword'))
    	->setAttrib('placeholder', 'Подтвердите пароль')
    	->setAttrib('autocomplete', 'off');
    	$name = new Zend_Form_Element_Text('regname');
    	$name->addFilter(new Zend_Filter_StringTrim())
    	
    	->setLabel('Имя')
    	->setAttrib('placeholder', 'Ваше имя')
    	;
    	$file = new Zend_Form_Element_File('avatar_');
    	$file->setValueDisabled(true)
    	->addValidator('Count',false,1)
    	->addValidator('Size',false,2097152)
    	->setMaxFileSize(2097152)
    	->addValidator('Extension',false,'png');
    	$submit = new Zend_Form_Element_Submit('save');
    	$submit->setLabel('Зарегестрироватся');
    	$this->addElements(array(
    			$email,
    			$frmPassword1,
    			$frmPassword2,
    			$name,
    			$file,
    			 $submit,
    			
    			));
    	
    }


}

