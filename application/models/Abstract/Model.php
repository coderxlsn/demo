<?php
abstract class Application_Model_Abstract_Model implements Application_Model_Abstract_Interface
{
	protected $_dbTable;
	protected $_row;
	protected $_db;
	
	public function __construct($id = null)
	{
		$this->_db = $this->_dbTable->getDefaultAdapter();
		if ($id !== null) {
			$row = $this->_dbTable->find((int)$id)->current();
			if (isset($row)) {
				$this->_row = $row;
			} else {
				throw new Zend_Db_Exception("item $id - not found");
			}
		} else {
			$this->_row = $this->_dbTable->createRow();
		}
	}
	
	public function create() {
		$this->_row = $this->_dbTable->createRow();
	}
	public function selectID($id)
	{
		$row = $this->_dbTable->find($id)->current();
		if (isset($row)) {
			$this->_row = $row;
		} else {
			throw new Exception("item $id - not found");
		}
		
	}
	public function save()
	{
		$this->_row->save();
	}
	public function delete()
	{
		$this->_row->delete();
	}
	public function fill($data)
	{
		foreach ($data as $key =>$value)
		{
			if(isset($this->_row->$key))
			{
				$this->_row->$key = $value;
			}
		}
	}
	public function __set($name,$val)
	{
		if(isset($this->_row->$name)){
			
			$this->_row->$name = $val;
		}
	}
	public function __get($name)
	{
		return $this->_row->$name;
	}
	public function fetchAll ($select = null)
	{
		return $this->_dbTable->fetchAll($select);
	}
	public function showRow()
	{
	    return $this->_row->toArray();
	}
	public function dashboard()
	{
		
		return $this->_dbTable->select();
	}
	public function count()
	{
		return $this->_dbTable->fetchAll()->count();
	}
	
}