<?php
require_once 'Zend/Http/Client.php';

class IndexController extends Zend_Controller_Action
{

	
    public function init()
    {
       //$translate = Zend_Registry::get("Zend_Translate");
	   //$this->view->translate = $translate;
		$this->dbObj = Zend_registry::get ( 'db' );
    }

    public function indexAction()
    {
    	
    	$em = Zend_Registry::get('em');
		$qb = $em->createQueryBuilder();
		$qb->select('a')->from('Entities\\AbstractPhp','a');
		
		$query = $qb->getQuery();
		$rows = $query->getResult();
		
		$this->view->person = $rows;
    	
    	
    /*$em = Zend_Registry::get("em");
		
		$connectionParams = array('dbname'=>'db_abstract', 'user'=>'root', 'password'=>'root', 'host'=>'localhost', 'driver'=>'pdo_mysql');
		$config = new \Doctrine\ORM\Configuration();
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver('E:\orchard'));
		$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
		$config->setProxyDir(__DIR__ . '/Proxies');
		$config->setProxyNamespace('Proxies');
		$config->setAutoGenerateProxyClasses(true);
		$em = \Doctrine\ORM\
		EntityManager::create($connectionParams, $config);
		
		// custom datatypes (not mapped for reverse engineering)
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('longblob', 'string');
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('blob', 'string');
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('varbinary', 'string');
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('tinyint', 'integer');
		
		// fetch metadata
		$driver = new \Doctrine\ORM\Mapping\Driver\
		DatabaseDriver($em->getConnection()->getSchemaManager());
		$em->getConfiguration()->setMetadataDriverImpl($driver);
		$cmf = new \Doctrine\ORM\Tools\
		DisconnectedClassMetadataFactory($em);
		$cmf->setEntityManager($em);
		$classes = $driver->getAllClassNames();
		$metadata = $cmf->getAllMetadata();
		$generator = new \Doctrine\ORM\Tools\
		EntityGenerator();
		$generator->setUpdateEntityIfExists(true);
		$generator->setGenerateStubMethods(true);
		$generator->setGenerateAnnotations(true);
		$generator->generate($metadata, 'C:\Documents and Settings\nidhi.singh\Desktop\Entities2');
		exit;*/
}


 public function addAction()
    {
    	$client = new Zend_Http_Client('http://localhost:9010/queue/server');
    	$response = $client->request();
    	//print_R($response->name);
    	
    	
		$Name = "";
		$this->view->Name = $Name;
		$website = "";
		$this->view->website = $website;
		
		
		
    }
    
 public function saveAction() {	
 	
 	
 	
 	$client = new Zend_Http_Client('http://localhost:9010/queue/server');
       //echo "--- END --";exit;
	   $id = $this->_request->getParam ( 'id' );
	   $Name = $this->_request->getParam ( 'Name' );
	   $website = $this->_request->getParam ( 'website' );
	   $client->setParameterGet(array('id'  => $id, 'action' => 'add','Name' =>$Name,'website'=>$website
	  ));
	   $response = $client->request();
       print_R($response->getBody());                     	
	
 
		
		/*$id = $this->_request->getParam ( 'id' );
		$em = Zend_Registry::get('em');
		$formData = $this->_request->getPost();
		$this->validate ( $formData );		
		try {
		$abstractphp = new Entities\AbstractPhp();
	    $abstractphp->setName($formData ['Name']);
		$abstractphp->setWebsite($formData['website']);
		
		
		$em->persist ($abstractphp);
        $em->flush();
 
		} catch ( Exception $ex ) {
			echo "<pre>";
			print_r ( $ex );
			exit ();
		}*/
	
}
  public function validate($formData) {
		$err = array ();
		if (count ( $formData ) > 0) {
			if ($formData ['Name'] == '' || $formData ['Name'] == null)
				$err ['Name'] = 'name is Required';
		}
		
		
		if (count ( $err ) > 0) {
			print_r ( $err );
			exit ();
		}
	} 

	


  public function changelanguageAction() {
    	
		$sessionData = new Zend_Session_Namespace('NidhiDemo');
		$ln = $this->_request->getParam ( "ln" );
		$sessionData->language =$ln;
		$this->_helper->json->sendJson(true);
	}


	
	public function clientAction() {
		
	   $client = new Zend_Http_Client('http://localhost:9010/queue/delete');
       //echo "--- END --";exit;
	   $id = $this->_request->getParam ( 'id' );
	   $client->setParameterGet(array('id'  => $id,  ));
	   $response = $client->request();
       print_R($response->getBody());                     	
	}
 
}