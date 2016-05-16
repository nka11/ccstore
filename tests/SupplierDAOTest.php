<?php

require_once("./model/SupplierDAO.php");
class SupplierDAOTest extends PHPUnit_Framework_TestCase
{
	public function testCreateSupplier(){
	  $supData = new Supplier(array(
		"email" => "supplier.testmail@email.test",
		"name" => "Testname Societe",
		"name_alias" => "Testname Alias",
		"address" => "1 test Societe Address",
		"zip" => "77000",
		"town" => "Testcity",
		"phone" => "0160340218",
		"web" => "www.web-societe.test"
	  ));
	$sdao = new SupplierDAO();
	
	$supplier = $sdao->createSupplier($supData);
	$this->assertNotInternalType('boolean', $supplier);
	$this->assertInternalType('int', $supplier->id());
	$this->assertInternalType('string', $supplier->email()); 
	$this->assertInternalType('string', $supplier->name());
	$this->assertInternalType('string', $supplier->name_alias());
	$this->assertInternalType('string', $supplier->address());
	$this->assertInternalType('string', $supplier->zip());
	$this->assertInternalType('string', $supplier->town());
	$this->assertInternalType('string', $supplier->phone());
	$this->assertInternalType('string', $supplier->web());
	$loadedsupplier = $sdao->getSupplierById($supplier->id());
	$this->assertNotInternalType('boolean', $loadedsupplier);
	$this->assertEquals($supplier->id(), $loadedsupplier->id());
	$this->assertEquals($supplier->email(), $loadedsupplier->email());
	$this->assertEquals($supplier->name(), $loadedsupplier->name());
	$this->assertEquals($supplier->name_alias(), $loadedsupplier->name_alias());
	$this->assertEquals($supplier->address(), $loadedsupplier->address());
	$this->assertEquals($supplier->zip(), $loadedsupplier->zip());
	$this->assertEquals($supplier->town(), $loadedsupplier->town());
	$this->assertEquals($supplier->phone(), $loadedsupplier->phone());
	$this->assertEquals($supplier->web(), $loadedsupplier->web());
	  
	$delsup = $custdao->deleteSupplier($supplier);  // delete supplier
	$this->assertTrue($delcust);  // confirm deletion
  }
	
  public function testGetSuppliers() {
    $suppliers = array();
	$societes = array();
	$delsoc = array();
	$sdao = new SupplierDAO();
	
	$suppliers[0] = new Supplier(array(
		"name" => "Testname1",
		"name_alias" => "Testnamealias1",
		"address" => "1 test address",
		"zip" => "77000",
		"town" => "testcity",
		"description" => "Test description 1"
	));
	$suppliers[1] = new Supplier(array(
		"name" => "Testname2",
		"name_alias" => "Testnamealias2",
		"address" => "2 test address",
		"zip" => "77000",
		"town" => "testcity",
		"description" => "Test description 2"
	));
	$suppliers[2] = new Supplier(array(
		"name" => "Testname3",
		"name_alias" => "Testnamealias3",
		"address" => "3 test address",
		"zip" => "77000",
		"town" => "testcity",
		"description" => "Test description 3"
	));
	foreach($suppliers as $key=>$supplier){
		$societes[$key] = $sdao->createCustomer($supplier); // Create societe
		$this->assertNotInternalType('boolean', $societes[$key]); // Confirm
	}
	
    $societes = $sdao->getSuppliers();
	$this->assertNotInternalType('boolean', $societes);
	$this->assertCount(3, $societes);
    foreach ($societes as $key=>$societe) {
        $this->assertEquals($suppliers[$key]->email(), $societes[$key]->email());
		$this->assertEquals($suppliers[$key]->name(), $societes[$key]->name());
		$this->assertEquals($suppliers[$key]->name_alias(), $societes[$key]->name_alias());
		$this->assertEquals($suppliers[$key]->address(), $societes[$key]->address());
		$this->assertEquals($suppliers[$key]->zip(), $societes[$key]->zip());
		$this->assertEquals($suppliers[$key]->town(), $societes[$key]->town());
		$this->assertEquals($suppliers[$key]->phone(), $societes[$key]->phone());
		$delsoc[$key] = $sdao->deleteSupplier($societe);
		$this->assertInternalType('boolean', $delsoc[$key]);  // confirm deletion
		$this->assertTrue( $delsoc[$key]);  // confirm deletion
    }
  }
  
}

