<?php

/**
 * Bootstrap
 * 
 * Главный загрузчик ресурсов приложения
 * @package Aplication
 * @author smotrisport
 * @copyright artur
 * @version 2012
 * @access public
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Bootstrap::_initDoctype()
     * 
	 * Инициализируем заголовок документа и тайтл по умолчанию
	 * @return object view
	 */
	protected function _initDoctype ()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
		$view->headTitle()->append('SmotriSport.TV');
		$view->headTitle()->setSeparator(' | ');
		//$view->headMeta()->appendHttpEquiv('Content-Type','text/html; charset=utf-8');
	}
	
	/**
	 * Bootstrap::_initLayoutHelper()
     * 
	 * Подключаем плагин переключения Layout
	 * @return
	 */
	protected function _initLayoutHelper()
	{
	
		$this->bootstrap ( 'frontController' );
		$layout = Zend_Controller_Action_HelperBroker::addHelper(
				new My_LayoutLoader());
		
	}
	/**
	 * Bootstrap::_initConfig()
	 * 
	 * @return
	 */
	protected function _initConfig ()
	{
		$config = new Zend_Config($this->getOptions(), true);
		Zend_Registry::set('config', $config);
		return $config;
	}
	/**
	 * Bootstrap::_initCache()
	 * 
	 * @return
	 */
	protected function _initCache ()
	{
	/* 	*/$frontendOptions = array(
				'lifetime' => 3600,
				'automatic_serialization' => true
		);
		 
		$backendOptions = array(
				'connection_string'  => 'mongodb://localhost',
				'database'           => 'smotrisport_db',
				'collection'         => 'smotrisport_collection',
				'persistent_connect' => false
		);
		
		$cache = Zend_Cache::factory('Core',
				'Mongo',
				$frontendOptions,
				$backendOptions
		);
		/* */
	/*	$frontendOptions = array('lifetime' => 3600,
				'debug_header' => false,  // for debugging
				'automatic_serialization' => true,
				'regexps' => array( // cache the whole IndexController
						'^/sitemap.xml' => array('cache' => false),
						'^/admin/' => array('cache' => false),
						'^/admin' => array('cache' => false),
						// cache the whole IndexController
						'^/index/' => array('cache' => true)));
		$backendOptions = array('cache_dir' => APPLICATION_PATH . "/data/cache");
		$cache = Zend_Cache::factory('Page', 'File', $frontendOptions,
				$backendOptions);

	/*	$cache_backend = new Zend_Cache_Backend_Memcached(
				array('servers' => array(array('host' => '127.0.0.1', 'port' => '11211')),
						'compression' => true));
		$cache_frontend = new Zend_Cache_Core(
				array('caching' => true, 'cache_id_prefix' => 'windowsdeveloper',
						//'logging' => true, 'logger' => $cache_logger,
						'write_control' => true,
						'doNotTestCacheValidity'=>true,
						'automatic_serialization' => true,
						'ignore_user_abort' => true, 'lifetime' => 7200));
		 
		// Инициализируем кэш объект
		$cache = Zend_Cache::factory($cache_frontend, $cache_backend);
/*	*/	
		// $cache->remove('main');
		//$cacheid = md5($_SERVER['REQUEST_URI']);
		Zend_Registry::set('cache', $cache);
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
		//$cache->start($cacheid, array());
		return $cache;
	}
	
	/**
	 * Bootstrap::_initSyslog()
	 * 
	 * @return
	 */
	protected function _initSyslog ()
	{
		// Zend_Log
		//$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '../var/logs/zf_log.txt'); // Websites/akrabat/application/var/logs/
		$writer = new Zend_Log_Writer_Stream(
				APPLICATION_PATH . '/data/logs/zf_log.txt'); // Websites/akrabat/var/logs/
		$logger = new Zend_Log($writer);
		Zend_Registry::set('logger', $logger);
		return $logger;
	}
	
	/*protected function _initDb()
	{
		$app_config = Zend_Registry::get('config'); //получаю конфиг
		$db = Zend_Db::factory($app_config->resources->db->adapter,   //создаю подключение в БД
				$app_config->resources->db->params->toArray());
		Zend_Db_Table_Abstract::setDefaultAdapter($db);   //установка адаптера по-умолчанию для всех Zend_Db_Table
		// Zend_Registry::set('Db',$db);
	}
	*/
	/**
	 * Bootstrap::_initRouter()
	 * 
	 * @return
	 */
	protected function _initRouter ()
	{
	
		// get cache for config files
		//$cacheManager = $this->bootstrap('cachemanager')->getResource(
		//        'cachemanager');
		//$cache = $cacheManager->getCache('configFiles');
	
		$cache = Zend_Registry::get('cache');
		$cacheId = 'routesini';
		$frontController = Zend_Controller_Front::getInstance();
	
		if (! $router = $cache->load($cacheId)) {
	
			// Load up .ini file and put the results in the cache
			$routes = new Zend_Config_Ini(
					APPLICATION_PATH . '/configs/router.ini', 'production');
			$router = $frontController->getRouter();
			$router->addConfig($routes, 'routes');
	
			$cache->save($router, $cacheId);
		} else {
			// Use cached version
			$frontController->setRouter($router);
		}
	
	
		//$router = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini',
		//         'production');
	
	
		return $router;
	}
	
	
	
	
	
	/*  
	protected function _initZFDebug() {
	    //if (Zend_Auth::getInstance ()->hasIdentity ()) {
    //$identity = Zend_Auth::getInstance ()->getIdentity ();
    
    $autoloader = Zend_Loader_Autoloader::getInstance ();
    $autoloader->registerNamespace ( 'ZFDebug' );
     
    $options = array ('plugins' => array ('Variables', 'File' => array ('base_path' => APPLICATION_PATH . '/../' ), 'Html', 'Memory', 'Time', 'Registry', 'Exception' ));
    # Instantiate the database adapter and setup the plugin.
    # Alternatively just add the plugin like above and rely on the autodiscovery feature.
    if ($this->hasPluginResource ( 'db' )) {
    $this->bootstrap ( 'db' );
    $db = $this->getPluginResource ( 'db' )->getDbAdapter ();
    $options ['plugins'] ['Database'] ['adapter'] = $db;
    }
    # Setup the cache plugin
    if ($this->hasPluginResource ( 'cache' )) {
    $this->bootstrap ( 'cache' );
    $cache = $this->getPluginResource ( 'cache' )->getDbAdapter ();
    $options ['plugins'] ['Cache'] ['backend'] = $cache->getBackend ();
    }
     
    $debug = new ZFDebug_Controller_Plugin_Debug ( $options );
     
    $this->bootstrap ( 'frontController' );
    $frontController = $this->getResource ( 'frontController' );
    $frontController->registerPlugin ( $debug );
    //}
    } 
	/*	*/
}

