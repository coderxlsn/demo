<?php

class V1_LotController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }



	public function headAction()
    {
    	// you should add your own logic here to check for cache headers from the request
    	$this->getResponse()->setBody(null);
    }
    
    
    

    public function indexAction()
    {
        // action body
        $model  =new Application_Model_Shop();
        
    	 $this->_helper->json($model->fetchAll()->toArray());
    }
    public function getAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	
    	$model= new Application_Model_Shop();
    	$res = $model->findLot($id);
    	if ($res) {
    		$this->_helper->json($res->toArray());	;
    	}else {
    		$this->_helper->json(array('error'=>'Запись не найдена'));
    	}
    		
    	
    }
    public function putAction()
    {
    	$this->_helper->json(array('action' => 'put'));
    }
    public function deleteAction()
    {
    	
    }
    public function postAction()
    {
    	
    }


}

