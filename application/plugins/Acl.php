<?php
/**
 * Application_Plugin_Acl
 *
 * Плагин авторизации и ролей в админку
 * @package Aplication
 * @subpackage Plugin\Acl
 * @author smotrisport
 * @copyright artur
 * @version 2013
 * @access public
 *
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	private $_acl = null;
	private $_auth = null;
	public function __construct(Zend_Acl $acl, Zend_Auth $auth) {
		$this->_acl = $acl;
		$this->_auth = $auth;
	}
	/**
	 * переопределяем действие плагина
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		$controller = $request->getControllerName ();
		$module = $request->getModuleName ();
		$action = $request->getActionName ();
		
		// получаем доступ к хранилищу данных Zend,
		// и достаём роль пользователя
		$identity = $this->_auth->getStorage ()->read ();
		
		// если в хранилище ничего нет, то значит мы имеем дело с гостем
		$role = ! empty ( $identity->role ) ? $identity->role : 'guest';
		if ($module == 'default'){
		if (! $this->_acl->isAllowed ( $role, $controller, $action )) {
			if (! $this->_auth->getIdentity() )
			{
				$request->setModuleName('default')->setControllerName('user')->setActionName('login');
				return 0;
			}else {	
				$request->setModuleName('default')->setControllerName ( 'error' )->setActionName ( 'deny' );
				return 0;
			}
		}
		}
	
	}
}