<?php
/**
 * Application_Model_User
 *
 * Модель юзеров для админ части
 * @package Aplication
 * @subpackage Model\User
 * @author smotrisport
 * @copyright artur
 * @version 2013
 * @access public
 *
 */
class Application_Model_User extends Application_Model_Abstract_Model
{
	private $keys = array( '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',);
	public function __construct($id = null)
	{
		$this->_dbTable = new Application_Model_DbTable_User();
		parent::__construct($id);
	}

	/**
	 * Авторизация в админку
	 * @param unknown $user
	 * @param unknown $pass
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
							'username',
							'password',
							'sha1(CONCAT(?,solt)) and status = 1');
		$authAdapter->setIdentity($user);
		$authAdapter->setCredential($pass);
		
		$result = $auth->authenticate($authAdapter);
		if($result->isValid()){
			$storage = $auth->getStorage();
			$data = $authAdapter->getResultRowObject(null,array('password','solt'));
			$profile = new Application_Model_Profile();
			$res = $profile->getProfile($data->id);
			if ($res)
			{
				$data->avatar = $res->avatar;
				$data->fullname = $res->fullname;
				
			}
			$storage->write($data);
			// Получить объект Zend_Session_Namespace
            $session = new Zend_Session_Namespace('Zend_Auth');
            // Установить время действия залогинености
            $session->setExpirationSeconds(24*3600);
	    //$this->view->user = Zend_Auth::getInstance()->getIdentity();    
            
            $this->selectID($data->id);
            $this->lastip = ip2long($ip);
            $this->lastlogin = time();
            $this->save();
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
		$active = $this->genKey();
		$this->_row = $this->_dbTable->createRow();
		$this->_row->name = $data->name;
		$this->_row->username = trim(strtolower($data->username));
		$this->_row->status =0;
		$this->_row->role = 'user';
		
		$this->_row->regip = ip2long($data->ip);
		$this->_row->regdate = time();
		$this->_row->lastip = 0;
		$this->_row->lastlogin = 0;
		$pass = $this->genPasswod($data->pass);
		$this->_row->password = $pass['pass'];
		$this->_row->solt = $pass['solt'];
		$this->_row->active = $active;
		$this->_row->timeactive = time()+(3600*72);
		$this->_row->save();
		return $this->_row;
	}
	public function activeUser($id,$key)
	{
		
		$res = $this->_dbTable->fetchRow(array('status = 0','active = ?'=>$key,'id = ?'=>$id,'timeactive > ?'=>time()));
		
		if ($res) {
			$res->status = 1;
			$res->active = 0;
			
			$res->save();
			return 'ok';
		}else {
			return 'error';
		}
	}
	/**
	 * проверяем валидность данных
	 * @param unknown $id
	 * @param unknown $key
	 * @return string
	 */
	public function resetPass($id,$key)
	{
	
		$res = $this->_dbTable->fetchRow(array('status = 1','active = ?'=>$key,'id = ?'=>$id,'timeactive > ?'=>time()));
	
		if ($res) {
			
			$res->active = 0;
				
			$res->save();
			return 'ok';
		}else {
			return 'error';
		}
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
	/**
	 * Генерируем ключ активации
	 * @return string
	 */
	public function genKey()
	{
		$keys = $this->keys;
		$k = mt_rand(0,count($keys)-1);
		$key = 'S-'.$this->selectKey(5, $keys[$k]).'-'.$this->selectKey(15, $keys[$k]).'-'.$this->selectKey(15, $keys[$k]).'-'.$this->selectKey(15, $keys[$k]);

		return $key;
	}
	/**
	 * Выбираем набор случайных букв ключа, размером $size
	 * @param размер слова $size
	 * @param ключ $key
	 * @return string
	 */
	private function selectKey($size,$key)
	{
		$result = '';
		$limit = strlen($key);
		if ($size > 1) {
			for ($i=0;$i<$size;$i++)
			{
				$result .= substr($key, mt_rand(1, $limit-1),1);
			}
		}
		return $result;
	}
	/**
	 * Генерируем случайный пароль
	 * @param unknown $size
	 */
	public function generatePass($size)
	{
		$keys = $this->keys;
		$k = mt_rand(0,count($keys)-1);
		return $this->selectKey($size, $keys[$k]);
	}
	/**
	 * поиск по ид
	 * @param unknown $id
	 * @return Ambigous <Zend_Db_Table_Row_Abstract, NULL, unknown>
	 */
	public function getUser($id)
	{
		return $this->_dbTable->fetchRow(array('id = ?'=>$id));
	}
	public function getUserForEmail($email)
	{
		return $this->_dbTable->fetchRow(array('username = ?'=>$email,'status = 1'));
	}
	/**
	 * Проверяем старый пароль
	 * @param unknown $id
	 * @param unknown $pass
	 */
	public function checkPass($id,$pass)
	{
		$select = $this->_dbTable->select()->where('id = ?',$id)
		->where('status = 1')
		->where('password = sha1(CONCAT(?,solt))', $pass);
		
		return $this->_dbTable->fetchRow($select);
	}
	public function setNewPass($id,$pass)
	{
		$this->selectID($id);
		$passs = $this->genPasswod($pass);
		$this->password = $passs['pass'];
		$this->solt = $passs['solt'];
		$this->save();
		return $this;
	}
	public function clearUser()
	{
		$res = $this->_dbTable->delete(array('status = 0','timeactive < ?'=>time()));
	}
	
}

