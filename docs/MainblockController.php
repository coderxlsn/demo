<?php

class Admin_MainblockController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    	$model = new Application_Model_Mainblock ();
    	$data = $model->dashboard ( $this->getRequest ()->getParam ( 'page' ) );
    	$this->view->content = $data;
    	$db = Zend_Db_Table::getDefaultAdapter ();
    	$adapter = new Zend_Paginator_Adapter_DbSelect ( $db->select ()->from ( 'mainblock' ) );
    	$paginator = new Zend_Paginator ( $adapter );
    	$paginator->setCurrentPageNumber ( $this->_getParam ( 'page' ) )->setDefaultItemCountPerPage ( 20 );
    	$this->view->paginator = $paginator;
    }

    public function addAction()
    {
        // action body
        $form = new Admin_Form_Mainblock();
        $model = new Application_Model_Mainblock();
        if ($this->getRequest()->isPost()) {
        	if ($form->isValid($this->getRequest()
        			->getPost())) {
        		$model->fill($form->getValues());
        		
        		$model->save();
        		$id = $model->id;
        		$path = realpath(dirname('.')).DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."main";
        		if (!is_dir($path) && strlen($path)>0)
        		{
        			mkdir($path,0755,true);
        		}
        		$upload = new Zend_File_Transfer_Adapter_Http();
        		$options = array('ignoreNoFile' => TRUE);
        		$upload->setOptions($options);
        		$upload->setDestination($path);
        		$files = $upload->getFileInfo();
        		$renameFilter = new Zend_Filter_File_Rename( $path );
        		foreach($files as $fileID => $fileInfo)
        		{
        			if($fileInfo['error']==0)
        			{
        				$renameFilter->addFile( array('source' => $fileInfo['tmp_name'], 'target' => $fileID.$id.'.jpg', 'overwrite' => true ) );
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
        		foreach($files as $fileID => $fileInfo)
        		{
        			if(is_file($path.DIRECTORY_SEPARATOR.$fileID.$id.".jpg"))
        			{
        				$img = new My_Im($path.DIRECTORY_SEPARATOR.$fileID.$id.".jpg");
        				$img->resizeImage(979, 392,'crop');
        				$img->saveImage($path.DIRECTORY_SEPARATOR.$fileID.$id."_b.jpg");
        				$img->resizeImage(245, 196,'crop');
        				$img->saveImage($path.DIRECTORY_SEPARATOR.$fileID.$id."_s.jpg");

        			}
        		}
        		
        		
        		$this->_redirect('admin/seopage');
        	}
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        // action body
    	$id = $this->getRequest()->getParam('id');
    	$form = new Admin_Form_Mainblock();
    	$model = new Application_Model_Mainblock($id);
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($this->getRequest()->getPost())) {
    			$data = $form->getValues();
    			unset($data['name']);
    			$model ->fill($data);
    			if (!isset($data['nofollow']))
    			{
    				$model->nofollow = 0;
    			}
    			$model ->save();
    			$id = $model->id;
    			$path = realpath(dirname('.')).DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."main";
    			if (!is_dir($path) && strlen($path)>0)
    			{
    				mkdir($path,0755,true);
    			}
    			$upload = new Zend_File_Transfer_Adapter_Http();
    			$options = array('ignoreNoFile' => TRUE);
    			$upload->setOptions($options);
    			$upload->setDestination($path);
    			$files = $upload->getFileInfo();
    			$renameFilter = new Zend_Filter_File_Rename( $path );
    			foreach($files as $fileID => $fileInfo)
    			{
    				if($fileInfo['error']==0)
    				{
    					$renameFilter->addFile( array('source' => $fileInfo['tmp_name'], 'target' => $fileID.$id.'.jpg', 'overwrite' => true ) );
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
    			foreach($files as $fileID => $fileInfo)
    			{
    				if(is_file($path.DIRECTORY_SEPARATOR.$fileID.$id.".jpg"))
    				{
    					$img = new My_Im($path.DIRECTORY_SEPARATOR.$fileID.$id.".jpg");
    					$img->resizeImage(979, 392,'crop');
    					$img->saveImage($path.DIRECTORY_SEPARATOR.$fileID.$id."_b.jpg");
    					$img->resizeImage(245, 196,'crop');
    					$img->saveImage($path.DIRECTORY_SEPARATOR.$fileID.$id."_s.jpg");
    			
    				}
    			}
    			$this->_redirect('admin/seopage');
    		}
    	} else {
    		$form->populate($model->showRow());
    	
    	}
    	$this->view->form = $form;
    }
    public function deleteAction() {
    	$id = $this->getRequest ()->getParam ( 'id' );
    	$model = new Application_Model_News ( $id );
    	$model->delete ();
    	$ref = $_SERVER ['HTTP_REFERER'];
    	$this->_redirect ( $ref );
    
    }


}





