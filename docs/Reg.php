<?php

class User_Form_Reg extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	$login = new Zend_Form_Element_Text('reglogin');
    	$login->setRequired('true')
    	->addValidator(new Zend_Validate_StringLength(3, 12), true)
    	->addValidator(new Zend_Validate_Db_NoRecordExists(
    			array(
    					'table'   => 'user',
    					'field'   => 'username',
    					 
    			)
    	),false)
    	
    	->addValidator(new Zend_Validate_Regex(array('pattern' => '/^[a-z0-9_]+$/')))
    	->setAttrib('placeholder', 'Ваше имя')
        ->setAttrib('data-name', 'Имя');
    	$frmPassword1=new Zend_Form_Element_Password('regpassword');
    	$frmPassword1->setLabel('Password')
    	->setRequired('true')
    	->addFilter(new Zend_Filter_StringTrim())
    	->addValidator(new Zend_Validate_NotEmpty())
    	->setAttrib('placeholder', 'Пароль')
        ->setAttrib('data-name', 'Пароль')
    	->setAttrib('autocomplete', 'off');
    	 
    	$frmPassword2=new Zend_Form_Element_Password('confirm_password');
    	$frmPassword2->setLabel('Confirm password')
    	->setRequired('true')
    	->addFilter(new Zend_Filter_StringTrim())
    	->addValidator(new Zend_Validate_Identical('regpassword'))
    	->setAttrib('placeholder', 'Подтвердите пароль')
        ->setAttrib('data-name', 'Подтвержение пароля')
    	->setAttrib('autocomplete', 'off');
    	$name = new Zend_Form_Element_Text('reglogin');
    	$name->addFilter(new Zend_Filter_StringTrim())
        ->setRequired('true')
        ->setAttrib('placeholder', 'Выберите логин')
        ->setAttrib('data-name', 'Логин')
        ->addValidator(new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9_]+$/')))
        ->addValidator(new Zend_Validate_StringLength(3, 12), true)
        ->addValidator(new Zend_Validate_Db_NoRecordExists(
        		array(
        				'table'   => 'user',
        				'field'   => 'name',
        
        		)
        ),false)
        ->setAttrib('autocomplete', 'off')
    	;
    	$email = new Zend_Form_Element_Text('regmail');
    	$email->addValidator(new Zend_Validate_EmailAddress())
    	->addFilter(new Zend_Filter_StringTrim())
    	->setAttrib('placeholder', 'Ваш e-mail')
        ->setAttrib('data-name', 'E-mail')
    	->addValidator(new Zend_Validate_Db_NoRecordExists(
                array(
                        'table'   => 'user',
                        'field'   => 'username',
                         
                )
        ),false)
        ->addErrorMessages(array(
        		Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND=>'Данный email уже используется'
        ))
        ->setAttrib('autocomplete', 'off')
    	;
    	$tel = new Zend_Form_Element_Text('tel');
    	$tel->addFilter(new Zend_Filter_StringTrim())
    	;
    	 
    	$this->addElements(array(
    			$name,
    			$frmPassword1,
    			$frmPassword2,
    			 
    			$email,
    			 
    			 
    			 
    	));
    	$this->setDecorators(array(array('ViewScript',array('viewScript' => 'form/regform.phtml'), 'Form')));
    }


}

