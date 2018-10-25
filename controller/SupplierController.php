<?php
require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class SupplierController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction(){
	$suppliers = $this->dbManager->loadSuppliers();
    return parent::render('suppliers.html', array(
		"suppliers" => $suppliers
	));
  }
	/**
	 * @Route("/:id")
	 * @Method("GET")
	 */
	 function itemAction($id){
		$supplier = $this->dbManager->getSupplier($id);
		if($supplier){
		 return parent::render('supplier_plug.html',array(
			"supplier" => $supplier,
			"suppliers"	=>	$suppliers
		 ));
		}
	 }
}
