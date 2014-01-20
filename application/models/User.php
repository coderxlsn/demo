<?php

class Application_Model_User extends Application_Model_Abstract_Model
{

	public function __construct($id = null)
	{
		$this->_dbTable = new Application_Model_DbTable_User();
		parent::__construct($id);
	}
	/**
	 * Авторизация в админку
	 * @param string $user
	 * @param string $pass
	 * @param number $remember
	 * @return boolean
	 */
	public function athorize($user,$pass,$ip,$remember = 0)
	{
		$user =trim(strtolower($user));
		$auth = Zend_Auth::getInstance();
		$authAdapter = new Zend_Auth_Adapter_DbTable(
				Zend_Db_Table::getDefaultAdapter(),
				'user',
				'email',
				'password',
				'sha1(CONCAT(?,solt)) and status = 1');
		$authAdapter->setIdentity($user);
		$authAdapter->setCredential($pass);
	
		$result = $auth->authenticate($authAdapter);
		if($result->isValid()){
			$storage = $auth->getStorage();
			$data = $authAdapter->getResultRowObject(null,array('password','solt'));
			$storage->write($data);
			// Получить объект Zend_Session_Namespace
			$session = new Zend_Session_Namespace('Zend_Auth');
			// Установить время действия залогинености
			$session->setExpirationSeconds(24*3600);
			// если отметили "запомнить"
			if ($remember == 1) {
				Zend_Session::rememberMe();
			}
			return true;
		}
		return false;
	}
	/**
	 * Регистрация нового юзера
	 * @param unknown $data
	 */
	public function createUser($data)
	{
		$this->_row = $this->_dbTable->createRow();
		$this->_row->name = $data['regname'];
		$this->_row->email = trim(strtolower($data['regmail']));
		$this->_row->status =1;
		$this->_row->role = 'user';
	
		;
		$pass = $this->genPasswod($data['regpassword']);
		$this->_row->password = $pass['pass'];
		$this->_row->solt = $pass['solt'];
		$this->_row->save();
		return $this->_row;
	}
	
	/**
	 * Генератор хешей пароля
	 * @param unknown $pass
	 * @return multitype:string
	 */
	public function genPasswod($pass)
	{
		$solt = base64_encode(mcrypt_create_iv(40,MCRYPT_DEV_URANDOM));
		return array('pass'=>sha1($pass.$solt),'solt' =>$solt);
	}
	
	public function findUser($id)
	{
		return $this->_dbTable->fetchRow(array('id = ?'=>$id,'status = 1'));
	}

}

