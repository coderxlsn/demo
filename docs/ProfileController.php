<?php

class User_ProfileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
        $reset = new User_Form_Reset();
    	
        $form = new User_Form_Profile();
        $ids = $this->getRequest()->getParam('id');
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity() ||$auth->getIdentity()->id != $ids ){
        	$this->redirect($this->view->url(array('id'=>$ids),'userpage'));
        }
        
        $user = $auth->getIdentity();
        $id = $user->id;
        $mProfile = new Application_Model_Profile();
        
        $profile=  $mProfile->getProfile($id);
        
        if ($this->getRequest()->isPost())
        {
        	if ($form->isValid($this->getRequest()->getPost()))
        	{
        		if ($profile)
        		{
        			$mProfile->selectID($id);
        		}else {
        			$mProfile->create();
        			$mProfile->id = $id;
        		}
        		$mProfile->fill($form->getValues());
        		
        		
        		
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
        		
        		if (isset($data['avatar_'])){
    				$i = realpath(dirname('.')).$data['avatar_'];
    				if(is_file($i))
    				{
    					$img2 = new My_Im($i);
    					$img2->resizeImage(150, 150,'crop');
    					$img2->saveImage($i);
    				}
    			
	    			$mProfile->avatar = $data['avatar_'];
	        		$storage = Zend_Auth::getInstance()->getStorage();
	        		$userdata = $storage->read();
	        		$storage->avatar =  $data['avatar_'];
	        		$storage->write($userdata);
        		}
        		$mProfile->save();
        		$this->redirect($this->view->url(array('id'=>$id),'userpage'));
        	}
        }else {
        	if ($profile) {
        		$form->populate($profile->toArray());
        	}
        }
        $this->view->form = $form;
        $this->view->reset = $reset;
    }
    public function resetAction()
    {
    	$auth = Zend_Auth::getInstance();
    	
    	if (!$auth->hasIdentity() ){
    		$this->redirect($this->view->url(array('id'=>$ids),'userpage'));
    	}
    	
    	$user = $auth->getIdentity();
    	$id = $user->id;
    	$form = new User_Form_Reset();
    	$result = new stdClass();
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($this->getRequest()->getPost()))
    		{
    			$model = new Application_Model_User();
    			if($model->checkPass($id, $form->getValue('oldpass')))
    			{
    				$model->setNewPass($id, $form->getValue('newspass'));
    				$result->error = 0;
    				$result->message = 'Пароль успешно изменен';
    			}else {
    				$result->error = 4;
    				$result->message = 'Старый пароль веден неверно';
    			}
    		}else {
    			$result->error = 5;
    			$result->message = $form->getMessages();
    		}
    	}
    	$this->_helper->json($result);
    }

    public function profileAction()
    {
        // action body
    	$id = $this->getRequest()->getParam('id');
    	if ($id < 10000) {
    		//$this->redirect('/');
    	}
    	$mProfile = new Application_Model_Profile();
    	$mUser = new Application_Model_User();
    	$user = $mUser->getUser($id);
    	
    	
    	if (!$user) {
    		$this->redirect('/');
    	}
    	$profile=  $mProfile->getProfile($id);
    	$this->view->profile = $profile;
        $this->view->user = $user;
    }


}





