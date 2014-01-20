<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype ()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
		$view->headTitle()->append('Демо магазин');
		$view->headTitle()->setSeparator(' | ');
	}
	protected function _initViewHelpers ()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		if (! Zend_Auth::getInstance()->hasIdentity()) {
			$view->indentity = false;
	
		} else {
			$view->indentity = Zend_Auth::getInstance()->getIdentity();
		}
	}
	protected function _initTranslate()
	{
		$translator = new Zend_Translate(
				'array',
				APPLICATION_PATH.'/resources/languages',
				'ru',
				array('scan' => Zend_Translate::LOCALE_DIRECTORY)
		);
		Zend_Validate_Abstract::setDefaultTranslator($translator);
	}
	protected function _initAcl ()
	{
		$auth = Zend_Auth::getInstance();
		// Определяем роль пользователя.
		// Если не авторизирован - значит "гость"
		$role = ($auth->hasIdentity() &&! empty($auth->getIdentity()->role)) ? $auth->getIdentity()->role : 'guest';
		$acl = new Zend_Acl();
		// Создаем роли
		$acl->addRole(
				new Zend_Acl_Role('guest'))
				->addRole(new Zend_Acl_Role('user'), 'guest') 
				->addRole(new Zend_Acl_Role('admin'), 'user');
	
		$acl->addResource(new Zend_Acl_Resource('index'))
		->addResource(new Zend_Acl_Resource('user'))
		->addResource(new Zend_Acl_Resource('shop'))
		->addResource(new Zend_Acl_Resource('error'))
		->addResource(new Zend_Acl_Resource('api'))
		;
		//global
		$acl->deny();
		$acl->allow('admin',null);
		// после авторизации логинится нельзя :-(
		$acl->deny('admin','user',array('reg','login'));
		$acl->deny('user','user',array('reg','login'));
		//guest
		$acl->allow('guest','user',array('login','reg'));
		$acl->allow('guest','error',null);
		$acl->allow('guest','api',null);
		//member
		$acl->allow('user','user',array('logout'));
		$acl->allow('user','shop',null);
	
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$view->acl = $acl;
		$fc = Zend_Controller_Front::getInstance();
		$fc->registerPlugin(new  Application_Plugin_Acl($acl, Zend_Auth::getInstance()));
		Zend_Registry::set('acl', $acl);
		return $acl;
	}
	
	protected function _initRouter() {
	
	
		$frontController = Zend_Controller_Front::getInstance();
		$route = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini',
				'production');
		$router = $frontController->getRouter();
		
		
	
		$router->addConfig($route, 'routes');
		$frontController->setRouter($router);
		return $router;
	}
	
	
}

