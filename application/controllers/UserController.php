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
    }

    public function regAction()
    {
        // action body
        $form = new Application_Form_Reg();
        if ($this->getRequest()->isPost()){
        	if ($form->isValid($this->getRequest()->getPost())) {
        		$model = new Application_Model_User();
        		$user = $model->createUser($form->getValues());
        		$id = $user->id;
        		$upload = new Zend_File_Transfer_Adapter_Http();
        		$path = realpath(dirname('.')).DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."user".DIRECTORY_SEPARATOR.substr($id, strlen($id)-2,2).DIRECTORY_SEPARATOR.substr($id, strlen($id)-4,2);
        		$url = '/upload/user/'.substr($id, strlen($id)-2,2).'/'.substr($id, strlen($id)-4,2);
        		if (!is_dir($path) && strlen($path)>0)
        		{
        			mkdir($path,0755,true);
        		}
        		$options = array('ignoreNoFile' => TRUE);
        		$upload->setOptions($options);
        		$upload->setDestination($path);
        		$files = $upload->getFileInfo();
        		$renameFilter = new Zend_Filter_File_Rename( $path );
        		foreach($files as $fileID => $fileInfo)
        		{
        			if($fileInfo['error']==0 && $upload->isUploaded($fileInfo['name']))
        			{
        				$info = pathinfo($fileInfo['name']);
        				$info = ".".$info['extension'];
        				 
        				$renameFilter->addFile( array('source' => $fileInfo['tmp_name'], 'target' => $fileID.$id.$info, 'overwrite' => true ) );
        				$data[$fileID]= $url.'/'.$fileID.$id.$info;
        			}
        		}
        		$upload->addFilter($renameFilter);
        		try
        		{
        			$upload->receive();
        		}
        		catch (Zend_File_Transfer_Exception $e)
        		{
        			$this->setErrorMessage( $e->getMessage() );
        		}
        		$this->view->message = "Вы успешо зарегистрированы. Перейдите на страницу <a href='/user/login'>логин</a> для входа на сайт";
        	}
        }
        $this->view->form = $form;
    }

    public function loginAction()
    {
        // action body
        $form = new Application_Form_Login();
        if ($this->getRequest()->isPost())
        {
        	if ($form->isValid($this->getRequest()->getPost())) {
        		$model = new Application_Model_User();
        		if ($model->athorize($form->getValue('email'), $form->getValue('regpassword'), $form->getValue('remember'))){
        			$this->_redirect('/shop/');
        		}else {
        			$this->view->message = "Логин и пароль не найдены";
        		}
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


}





