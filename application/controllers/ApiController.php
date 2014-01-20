<?php

class ApiController extends Zend_Rest_Controller
{

    public function init()
    {
        /* Initialize action controller here */
    	//$this->_helper->viewRenderer->setNoRender(true);
//    	$ajaxContext = $this->getHelper('AjaxContext');
//    	$ajaxContext->addActionContext('index', 'json');
//    	$ajaxContext->addActionContext('get', 'html');
//    	$ajaxContext->addActionContext('post', 'json');
 //   	$ajaxContext->addActionContext('put', 'json');
 //   	$ajaxContext->addActionContext('delete', 'json');
  //  	$ajaxContext->initContext();
    	 
    }
    public function headAction()
    {
    	// you should add your own logic here to check for cache headers from the request
    	$this->getResponse()->setBody(null);
    }
    
    
    

    public function indexAction()
    {
        // action body
    	 $this->_helper->json(array('action' => 'index'));
    }
    public function getAction()
    {
    	$this->_helper->json(array('action' => 'get'));
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

