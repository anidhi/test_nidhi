<?php

class QueueController extends Zend_Controller_Action
{

	
    public function init()
    {
        
    }

    public function indexAction()
    {
    	
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

    public function serverAction() 
 {
      if($_GET['action']=='add') {
      $id = $this->_request->getParam ( 'id' );
      $Name = $this->_request->getParam ( "Name" );
      $website = $this->_request->getParam ( "website" );
		$em = Zend_Registry::get('em');
		$formData = $_GET;
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
		}
     

  }
 	
 	/*else{
      $data=$_GET;
      $id=$data['id'];
      
        $em = Zend_Registry::get('em');
	    $id = $this->_request->getParam ( "id" );
	    $abPhp = $em->find('Entities\\AbstractPhp',$id);
		$em->remove($abPhp);
		$em->flush();
        //print_r($_GET);
     }*/
 }
 public function deleteAction()
  {
  	    $data=$_GET;
        $id=$data['id'];
      
        $em = Zend_Registry::get('em');
	    $id = $this->_request->getParam ( "id" );
	    $abPhp = $em->find('Entities\\AbstractPhp',$id);
		$em->remove($abPhp);
		$em->flush();
        //print_r($_GET);
 }

}


