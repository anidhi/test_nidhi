<?php
use Doctrine\ORM;
require '\Doctrine\Common\ClassLoader.php';
use Doctrine\ORM\EntityManager, Doctrine\ORM\Configuration;
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}
	
	protected function _initDb(){
		$config = new Zend_Config_Ini(CONFIG_PATH.'/application.ini', APPLICATION_ENV);
		Zend_Registry::set("NidhiDemo", $config);
		$configObj = Zend_Registry::get("NidhiDemo");
		
		try{
			$db = Zend_Db::factory($configObj->database->adapter, $configObj->database->params->toArray());
			Zend_Db_Table::setDefaultAdapter($db);
			
			Zend_Registry::set('db', $db);
			$db->query("SET NAMES 'utf8'");
			return $db;
		}catch(Exception $e){
			return null;
		}
	}
   protected function _initAutoload(){
		$moduleLoader = new Zend_Application_Module_Autoloader(array('namespace'=>'', 'basePath'=>APPLICATION_PATH));
		/** auto load */
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);
		return $moduleLoader;
	}
	
	protected function _initPluginload(){
		$controller = Zend_Controller_Front::getInstance();
		$controller->registerPlugin(new Web_Controller_Plugin_NidhiDemo());
	}

	
    protected function _initDoctrine()
	{
		$applicationConfig = Zend_Registry::get("NidhiDemo");
		
		$zendAutoloader = Zend_Loader_Autoloader::getInstance();
		// Autoload the doctrine objects
		$autoloader = array(new \Doctrine\Common\ClassLoader('Doctrine'), 'loadClass');
		$zendAutoloader->pushAutoloader($autoloader, 'Doctrine');
		// Autoload the models
		$autoloader = array(new \Doctrine\Common\ClassLoader('Entities', APPLICATION_PATH), 'loadClass');
		$zendAutoloader->pushAutoloader($autoloader, 'Entities');
		
		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
		$classLoader->register();
		 
		if (APPLICATION_ENV == 'development') {
			$cache = new \Doctrine\Common\Cache\ArrayCache;
		} else {
			$cache = new \Doctrine\Common\Cache\ApcCache;
		}
		 
		$entitiesPath = '../Entities';
		$proxiesPath    = '../proxies';
		 
		$config = new \Doctrine\ORM\Configuration();
		$config->setMetadataCacheImpl($cache);
		$driverImpl = $config->newDefaultAnnotationDriver($entitiesPath);
		$config->setMetadataDriverImpl($driverImpl);
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir($proxiesPath);
		$config->setProxyNamespace('Proxies');
		$config->setEntityNamespaces(array('Entities'));
		 
		if (APPLICATION_ENV == 'development') {
			$config->setAutoGenerateProxyClasses(true);
		} else {
			$config->setAutoGenerateProxyClasses(false);
		}
		 
		$doctrineConfig = $this->getOption('doctrine');
		/* $connectionOptions = array(
			'driver'    => 'pdo_mysql',
			'user'        => 'root',
			'password'        => 'root',
			'dbname'    => 'test_db',
			'host'        => 'localhost'
		); */
		
		$connectionOptions = array('dbname'=>$applicationConfig->database->params->dbname, 'user'=>$applicationConfig->database->params->username,
			'password'=>$applicationConfig->database->params->password, 'host'=>$applicationConfig->database->params->host,
			'driver'=>$applicationConfig->database->adapter);
		 
		$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
		Zend_Registry::set('em', $em);
		 
		return $em;
	}
}