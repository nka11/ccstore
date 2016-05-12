<?php

require_once("./model/CustomerDAO.php");
require_once("./model/class/Customer.class.php");
class CustomerDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

  public function testGetCustomers() {
    $custdao = new CustomerDAO();
    $custdao->getCustomersList();
  }
  public function testGetUnexistingCustomerByEmail() {
    $custdao = new CustomerDAO();
    $customer = $custdao->getCustomerByEmail("noclient@testDomain.com");
    $this->assertInternalType('boolean',$customer);
    $this->assertFalse($customer);
  }
  public function testGetCustomerAdherentByEmail() {
    $custdao = new CustomerDAO();
    $customer = $custdao->getCustomerByEmail("testClientAdherent@testDomain.com");
  }
  /*
  public function testGetCustomerByEmail() {
    $custdao = new CustomerDAO();
    $customer = $custdao->getCustomerByEmail("testclient2@testdomain.com");
  } */
  /*
  public function testCreateCustomer() {
    $custData = new Customer(array(
      "name" => "test un",
      "firstname" => "test prenom",
      "email" => "test@email.test",
      "password" => "testPassword"
    ));
    $custdao = new CustomerDAO();
    $customer = $custdao->createCustomer($custData);
    $this->assertNotInternalType('boolean',$customer);
    $this->assertInternalType('int',$customer->id_c());
    $newCustomer = $custdao->getCustomerById($customer->id_c());
    $this->assertEquals($customer->id_c(),$newCustomer->id_c());
    $this->assertEquals($customer->nom(),$newCustomer->nom());
    $this->assertEquals($customer->email(),$newCustomer->email());
    $customer->setAddress("123 arava");
    $customer->setZip("12345");
    $customer->setTown("Paris");
    $custdao->updateCustomer($customer);
    $newCustomer = $custdao->getCustomerById($customer->id_c());
    $this->assertEquals($customer->address(),$newCustomer->address());
    $this->assertEquals($customer->ville(),$newClient->town());
    $this->assertEquals($customer->zip(),$newClient->zip());
    $delres = $custdao->deleteCustomer($newCustomer);
    $this->assertInternalType('boolean',$delres);
    $this->assertTrue($delres);
    $newCustomer = $custdao->getCustomerById($customer->id_c());
    $this->assertInternalType('boolean',$newCustomer);
    $this->assertFalse($newCustomer);
  }
  */
  public function testCreateCustomer(){
	  $custData = new Customer(array(
		"email" => "johann.bernhard26@gmail.com",
		"name" => "Bernhard",
		"firstname" => "Johann",
		"password" => "tatata",
		"address" => "24 rue Erik Satie",
		"zip" => "77380",
		"town" => "Combs-la-Ville",
		"phone" => "0160340218"
	  ));
	  $custdao = new CustomerDAO();
	  $customer = $custdao->createCustomer($custData);
	  $this->assertNotInternalType('boolean',$customer);
	  $this->assertInternalType('int',$customer->id_c());
	  $this->assertInternalType('int', $customer->id_user());
	  $this->assertInternalType('string',$customer->email()); 
	  $this->assertInternalType('string',$customer->name());
	  $this->assertInternalType('string',$customer->firstname());
	  $this->assertInternalType('string',$customer->password());
	  $this->assertInternalType('string',$customer->address());
	  $this->assertInternalType('string',$customer->zip());
	  $this->assertInternalType('string',$customer->town());
	  $this->assertInternalType('string',$customer->phone());
	  $copycustomer = $custdao->getCustomerById($customer->id_c());
	  $this->assertNotInternalType('boolean', $copycustomer);
	  $this->assertEquals($customer->id_c(), $copycustomer->id_c());
	  $this->assertEquals($customer->id_user(), $copycustomer->id_user());
	  $this->assertEquals($customer->email(), $copycustomer->email());
	  $this->assertEquals($customer->name(), $copycustomer->name());
	  $this->assertEquals($customer->firstname(), $copycustomer->firstname());
	  $this->assertEquals($customer->address(), $copycustomer->address());
	  $this->assertEquals($customer->zip(), $copycustomer->zip());
	  $this->assertEquals($customer->town(), $copycustomer->town());
	  $this->assertEquals($customer->phone(), $copycustomer->phone());
	  $copycustomer = $custdao->getCustomerByEmail($customer->email());
	  $this->assertNotInternalType('boolean', $copycustomer);
	  $this->assertEquals($customer->id_c(), $copycustomer->id_c());
	  $this->assertEquals($customer->id_user(), $copycustomer->id_user());
	  $this->assertEquals($customer->email(), $copycustomer->email());
	  $this->assertEquals($customer->name(), $copycustomer->name());
	  $this->assertEquals($customer->firstname(), $copycustomer->firstname());
	  $this->assertEquals($customer->address(), $copycustomer->address());
	  $this->assertEquals($customer->zip(), $copycustomer->zip());
	  $this->assertEquals($customer->town(), $copycustomer->town());
	  $this->assertEquals($customer->phone(), $copycustomer->phone());
  }
  
}

 
