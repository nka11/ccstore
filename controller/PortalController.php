<?php

require_once './vendor/autoload.php';
//require_once './controller/cManager.php';
require_once './controller/AbstractController.php'; // *TO DO* : DELETED (sent to cManager).
//class PortalController extends cManager { 
class PortalController extends AbstractController { // *TO DO* : DELETED.

  /**
   * @Route("/")
   * @Method("GET")
   */
  public function indexAction(){
	$selectedRefs= array( "JAMBGALE", "LBABCEDE", "ROMACVSJ");
	$count_products= count($this->dbManager->loadProducts( NULL, TRUE));
	foreach( $selectedRefs as $ref){
		$exemples[] = $this->dbManager->loadProduct($ref);
	}
	$categories= $this->dbManager->loadCategories();
	return parent::render('portal.html', array(
											"categories"=>$categories,
											"count_products"=>$count_products,
											"exemples"=>$exemples
										));
  }
  /**
   * @Route("/lamarge")
   * @Method("GET")
   */
  public function LaMargeAction(){
	return parent::render('portail/lamarge.html', array());
  }
}