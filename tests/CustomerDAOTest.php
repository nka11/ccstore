<?php

require_once("./model/CustomerDAO.php");
require_once("./model/class/Customer.class.php");
class CustomerDAOTest extends PHPUnit_Framework_TestCase
{
    // ...
	public function testLoginCaseUnknownUser(){
		$custdao = new CustomerDAO();
		
		// Case user does not exist
		$email = "unknownUser@email.test";
		$password = "testPassword";
		$customer = $custdao->getCustomerByEmail($email);
		$this->assertInternalType('boolean',$customer);
	}
	
	public function testLoginCaseKnownUserAndPwsMatch(){
		$custdao = new CustomerDAO();
		
		//Case user does exist and password match
		$existingcustomer = new Customer(array(
			"email" => "test1@email.test",
			"password" => "test1Password",
			"name" => "Testname",
			"firstname" => "Testfirstname",
			"address" => "1 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
		$existingcustomer = $custdao->createCustomer($existingcustomer);
		$this->assertNotInternalType('boolean', $existingcustomer);
		$email = "test1@email.test";
		$password = "test1Password";
		$this->assertEquals($password, $existingcustomer->password());
		$custdata = $custdao->getCustomerByEmail($email);
		$this->assertNotInternalType('boolean', $custdata);
		$this->assertNotEquals($password, $custdata->password());
		$custdata->setPassword($password);
		$custdata = $custdao->login($custdata);
		$this->assertNotInternalType('boolean', $custdata);
		$this->assertNotEquals(null, $custdata->api_key());
		$this->assertNotEquals("", $custdata->api_key());
	}
	
	public function testLoginCaseKnownUserAndPwsNotMatch(){
		$custdao = new CustomerDAO();
		
		$existingcustomer = new Customer(array(
			"email" => "test2@email.test",
			"password" => "test2Password",
			"name" => "Testname",
			"firstname" => "Testfirstname",
			"address" => "2 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
		// Case user does exist and password does not match
		$email = "test2@email.test";
		$password = "invalidPassword";
		$this->assertNotEquals($password, $existingcustomer->password());
		$custdata = $custdao->getCustomerByEmail($email);
		$this->assertNotInternalType('boolean', $custdata);
		$this->assertNotEquals($password, $custdata->password());
		$custdata->setPassword($password);
		$custdata = $custdao->login($custdata);
		$this->assertInternalType('boolean', $custdata);
	}
	/*
	public function testLoginWithInvalidFormEmail(){
		$custdao = new CustomerDAO();
		$email = null;
		$password = "testPassword";
		
	}
	*/
  public function testGetCustomers() {
    $custdao = new CustomerDAO();
    $custdao->getCustomers();
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

 
