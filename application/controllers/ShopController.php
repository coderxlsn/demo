<?php

class ShopController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $model = new Application_Model_Shop();
        $adapter = new Zend_Paginator_Adapter_DbSelect ( $model->dashboard());
    	$paginator = new Zend_Paginator ( $adapter );
    	$paginator->setCurrentPageNumber ( $this->_getParam ( 'page' ) )->setDefaultItemCountPerPage ( 20 );
    	$this->view->paginator = $paginator;
    }

    public function addAction()
    {
        // action body
        $form = new Application_Form_AddLot();
        if ($this->getRequest()->isPost()){
        	if ($form->isValid($this->getRequest()->getPost())) {
        		$model = new Application_Model_Shop();
        		$model->fill($form->getValues());
        		$model->save();
        		$this->_redirect('/shop/');
        	}
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        // action body
        $id =  $this->getRequest()->getParam('id');
        $form = new Application_Form_AddLot();
        $model = new Application_Model_Shop($id);
        if ($this->getRequest()->isPost()){
        if ($form->isValid($this->getRequest()->getPost())) {
        		//$model = new Application_Model_Shop();
        		$model->fill($form->getValues());
        		$model->save();
        		$this->_redirect('/shop/');
        	}
        }else {
        	$form->populate($model->showRow());
        }
        $this->view->form = $form;
    }


}





