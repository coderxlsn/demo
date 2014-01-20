<?php

class User_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    	$this->redirect('/');
    }

    public function regAction()
    {
        // action body
    	$auth =  Zend_Auth::getInstance();
    	if ($auth->hasIdentity())
    	{
    		$this->redirect("/");
    	}
        $form = new User_Form_Reg();
        $this->view->form = $form;
    }
    public function postdataAction()
    {
    	$result = new stdClass();
    	$form = new User_Form_Reg();
    	if ($this->getRequest()->isPost())
    	{
    		if ($form->isValid($this->getRequest()->getPost())) {
    			$result->error = 0;
    			$result->message = 'На указанный Вами email было отправлено письмо для подтверждения регистрации.';
    			if ($this->getRequest()->getServer('HTTP_X_REAL_IP') !== null) {
    				$ip = $this->getRequest()->getServer('HTTP_X_REAL_IP');
    			}else {
    				$ip = $this->getRequest()->getServer('REMOTE_ADDR');
    			}
    			$model = new Application_Model_User();
    			$data = new stdClass();
    			$data->name = $form->getValue('reglogin');
    			$data->username = $form->getValue('regmail');
    			$data->pass = $form->getValue('confirm_password');
    			$data->ip  = $ip;
    			$res = $model->createUser($data);
    			if ($res->id > 1) {
    				$mail = new Application_Model_Mail();
    				$mail->activateUser($form->getValue('regmail'), $form->getValue('reglogin'),$res->id,$res->active);
    				//$mail->send();
    			}
    		}else {
    			$result->message = $form->getMessages();
    			$result->error = 4;
    		}
    	}else {
    		$result->error = 5;
    		$result->message = 'Нет данных';
    	}
    	$this->_helper->json($result);
    }
    
    public function activeAction()
    {
    	$key = $this->getRequest()->getParam('key');
    	$id = $this->getRequest()->getParam('id');
    	$model = new Application_Model_User();
    	$this->view->status = $model->activeUser($id, $key);
    	
    }
    public function logoutAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	$this->redirect('/');
    }
    public function loginAction()
    {
    	$auth =  Zend_Auth::getInstance();
    	if ($auth->hasIdentity())
    	{
    		$id = Zend_Auth::getInstance()->getIdentity();
    		
    		$this->redirect($this->view->url(array('id'=>$id->id),'userpage'));
    	}
    	if ($this->getRequest()->isPost())
    	{
    		$email = $this->getRequest()->getParam('email');
    		$pass = $this->getRequest()->getParam('pass');
    		$remember = $this->getRequest()->getParam('remember');
    		if ($this->getRequest()->getServer('HTTP_X_REAL_IP') !== null) {
    			$ip = $this->getRequest()->getServer('HTTP_X_REAL_IP');
    		}else {
    			$ip = $this->getRequest()->getServer('REMOTE_ADDR');
    		}
    		$eValide = new Zend_Validate_EmailAddress();
    		if ($eValide->isValid($email))
    		{
    			$model = new Application_Model_User();
    			$auth = $model->athorize($email, $pass, $ip,$remember);
    			if ($auth) {
    				$id = Zend_Auth::getInstance()->getIdentity();
    				$this->redirect($this->view->url(array('id'=>$id->id),'userpage'));
    			}else {
    				$this->view->message = 'Логин и пароль не найдены';
    			}
    		}else {
    			$this->view->message = 'Невалидный email';
    		}
    	}
    }
    public function loginformAction()
    {
    	$this->_helper->viewRenderer->setNoRender ( true );
    	$ref = $this->getRequest()->getServer('HTTP_REFERER');
    	if (!$ref)
    	{
    		$ref = '/';
    	}
    	$auth =  Zend_Auth::getInstance();
    	if ($auth->hasIdentity())
    	{
    		$this->redirect($ref);
    	}
    	if ($this->getRequest()->isPost())
    	{
    		$email = $this->getRequest()->getParam('email');
    		$pass = $this->getRequest()->getParam('pass');
    		$remember = $this->getRequest()->getParam('remember');
    		if ($this->getRequest()->getServer('HTTP_X_REAL_IP') !== null) {
    			$ip = $this->getRequest()->getServer('HTTP_X_REAL_IP');
    		}else {
    			$ip = $this->getRequest()->getServer('REMOTE_ADDR');
    		}
    		$res = new stdClass();
    		$eValide = new Zend_Validate_EmailAddress();
    		if ($eValide->isValid($email))
    		{
    			$model = new Application_Model_User();
    			$auth = $model->athorize($email, $pass, $ip,$remember);
    			
    			if ($auth) {
    				$res->error= 0;
    				
    			}else {
    				$res->error = 4;
    				$res->message = 'Логин и пароль не найдены';
    			}
    		}else {
    			$res->error = 5;
    			$res->message = 'Email веден не верно';
    		}
    		$this->_helper->json($res);
    	}
    	//$this->redirect($ref);
    	
    }
    public function resetAction()
    {
    	$auth =  Zend_Auth::getInstance();
    	if ($auth->hasIdentity())
    	{
    		$this->redirect('/');
    	}
    	
    	$email = $this->getRequest()->getParam('email');
    	if ($this->getRequest()->isPost())
    	{
    		$result = new stdClass();
    		
    		$eValide = new Zend_Validate_EmailAddress();
    		if (!$email)
    		{
    			$result->error = 4;
    			$result->message = 'Поле email не заполнено';
    		}elseif ($eValide->isValid($email))
    		{
    			$model = new Application_Model_User();
    			$user = $model->getUserForEmail($email);
    			if ($user) {
    				$key = $model->genKey();
	    			$model->selectID($user->id);
    				$model->active = $key;
    				$model->timeactive = time()+(3600*24*3);
    				
    				$model->save();
    				$mail = new Application_Model_Mail();
	    			$mail->resetPass($email, $user->name, $user->id, $key);
	    			
	    			$result->error = 0;
	    			$result->message = 'Данные успешно отправлены на почту';
    			}else {
    				$result->error = 4;
    				$result->message = 'Юзер с данным email не найден';
    			}
    			
    		}else {
    			$result->error = 5;
    			$result->message = $eValide->getMessages();
    		}
    		$this->view->result = $result;
    	}
    }
    public function resetpassAction()
    {
    	$key = $this->getRequest()->getParam('key');
    	$id = $this->getRequest()->getParam('id');
    	if ($id > 0 && strlen($key) ==55){
	    	$model = new Application_Model_User();
	    	$status = $model->resetPass($id, $key);
	    	if ($status == 'ok')
	    	{
	    		$pass = $model->generatePass(8);
	    		$mail = new Application_Model_Mail();
	    		
	    		$model->setNewPass($id, $pass);
	    		$user = $model->getUser($id);
	    		$mail->resetActivePass($user->username, $user->name, $pass);
	    	}
    	}else {
    		$status = 'error';
    	}
    	$this->view->status = $status;
    }
    
    


}



