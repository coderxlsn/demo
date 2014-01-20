<?php

class Application_Model_Shop extends Application_Model_Abstract_Model
{

	public function __construct($id = null)
	{
		$this->_dbTable = new Application_Model_DbTable_Shop();
		parent::__construct($id);
	}
	public function findLot($id)
	{
		return $this->_dbTable->fetchRow(array('id = ?'=>$id));
	}

}

