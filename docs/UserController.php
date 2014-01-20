<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    	$model = new Application_Model_Members();
    	$this->view->pass = $model->genHash('newpass');
    	//var_dump($model->getShowUserSql());

    	
    //	*/
    }
    public function loginAction()
    {
    	$form = new Application_Form_Login();
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
    	{
    		$user = new Application_Model_Members();
    		if($user->athorize($form->getValue('email'), $form->getValue('regpassword'),$form->getValue('remember')))
    		{
    			$this->_redirect($this->getRequest()->getParam('url'));
    		}	
    	}
    	$this->view->form = $form;
    	
    		 
    }
    public function logoutAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	$this->_redirect("/");
    	 
    }
    public function regAction()
    {
    	$form = new Application_Form_Reg();
    	if ($this->getRequest()->isPost())
    	{
    		if ($form->isValid($this->getRequest()->getPost()))
    		{
    			$model = new Application_Model_Members();
    			$model->regUser($form->getValues());
    		}
    	}
    	$this->view->form = $form;
    }
    public function profileAction()
    {
    	
    	$form = new Application_Form_Profile();
    	$form2 = new Application_Form_Respass();
    	$auth = Zend_Auth::getInstance()->getIdentity();
    	$id = $auth->id;
    	$model = new Application_Model_Profile();
    	$mUser = new Application_Model_Members();
    	$model->checkId($id);
    	if ($this->getRequest()->isPost())
    	{
    		$mode = $this->getRequest()->getParam('mode');
    		if($mode == 1)
    		{
    			if ($form->isValid($this->getRequest()->getPost()))
    			{
    				echo 'valide';
    				var_dump($form->getValues());
    			}
    		}else {
    			if ($form2->isValid($this->getRequest()->getPost()))
    			{
    				if($mUser->getCurrentPass($id, $form2->getValue('curpass'))){
	    				echo 'valide2';
	    				var_dump($form2->getValues());
    				}
    			}
    		}
    	}else {
    		
    		$form->populate($model->showRow());
    	}
    	$this->view->form = $form;
    	
    	
    	$this->view->form2 = $form2;
    }
    


}

