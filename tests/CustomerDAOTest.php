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
		$existingcustomer = $custdao->createCustomer($existingcustomer); // Create knownUser
		$this->assertNotInternalType('boolean', $existingcustomer); // Confirm
		$knownuser = $custdao->getCustomerByEmail($existingcustomer->email()); // load knownUser
		$this->assertNotInternalType('boolean', $knownuser);  // Confirm loading
		$this->assertNotEquals($existingcustomer->password(), $knownuser->password());  // Confirm knownUser password is crypted
		$knownuser->setPassword($existingcustomer->password());   // setPassword
		$custdata = $custdao->login($knownuser);  // Test login
		$this->assertNotInternalType('boolean', $custdata);  // confirm login
		$this->assertNotEquals(null, $custdata->api_key());  // confirm api_key not null
		$this->assertNotEquals("", $custdata->api_key());  // confirm api_key not empty
		$delcust = $custdao->deleteCustomer($knownuser);  // delete knownuser
		$this->assertInternalType('boolean', $delcust);  // confirm deletion
		$this->assertEquals(1, $delcust);  // confirm deletion
		$this->assertNotEquals(0, $delcust);  // confirm deletion
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
		$this->assertNotEquals("invalidPassword", $existingcustomer->password()); // Assert passwords not match
		$knownuser = $custdao->createCustomer($existingcustomer); // Create knownUser
		$this->assertNotInternalType('boolean', $knownuser); // Confirm
		$knownuser = $custdao->getCustomerByEmail($existingcustomer->email()); // load knownuser
		$this->assertNotInternalType('boolean', $knownuser); // confirm loading
		$this->assertNotEquals($existingcustomer->password(), $knownuser->password()); // Assert knownuser's password is crypted
		$knownuser->setPassword("invalidPassword");
		$custdata = $custdao->login($knownuser);
		$this->assertInternalType('boolean', $custdata);
		$delcust = $custdao->deleteCustomer($knownuser);  // delete knownuser
		$this->assertInternalType('boolean', $delcust);  // confirm deletion
		$this->assertEquals(1, $delcust);  // confirm deletion
		$this->assertNotEquals(0, $delcust);  // confirm deletion
	}
	
  public function testGetCustomers() {
    $customers = array();
	$users = array();
	$delusers = array();
	$custdao = new CustomerDAO();
	
	$customers[0] = new Customer(array(
			"email" => "test1@email.test",
			"password" => "test1Password",
			"name" => "Testname1",
			"firstname" => "Testfirstname1",
			"address" => "1 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
	$customers[1] = new Customer(array(
			"email" => "test2@email.test",
			"password" => "test2Password",
			"name" => "Testname2",
			"firstname" => "Testfirstname2",
			"address" => "2 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
	$customers[2] = new Customer(array(
			"email" => "test3@email.test",
			"password" => "test3Password",
			"name" => "Testname3",
			"firstname" => "Testfirstname3",
			"address" => "3 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
	foreach($customers as $key=>$customer){
		$users[$key] = $custdao->createCustomer($customer); // Create user1
		$this->assertNotInternalType('boolean', $users[$key]); // Confirm
	}
	$users = $custdao->getCustomers();
	$this->assertInternalType('array', $users);
	$this->assertCount(3, $users);
	foreach($users as $key=>$user){
		$this->assertEquals($customers[$key]->email(), $users[$key]->email());
		$this->assertEquals($customers[$key]->name(), $users[$key]->name());
		$this->assertEquals($customers[$key]->firstname(), $users[$key]->firstname());
		$this->assertEquals($customers[$key]->address(), $users[$key]->address());
		$this->assertEquals($customers[$key]->zip(), $users[$key]->zip());
		$this->assertEquals($customers[$key]->town(), $users[$key]->town());
		$this->assertEquals($customers[$key]->phone(), $users[$key]->phone());
		$delusers[$key] = $custdao->deleteCustomer($user);
		$this->assertInternalType('boolean', $delusers[$key]);  // confirm deletion
		$this->assertEquals(1, $delusers[$key]);  // confirm deletion
		$this->assertNotEquals(0, $delusers[$key]);  // confirm deletion
	}
	
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
	  
	  if(!$customer){
		 $delcust = $custdao->deleteCustomer($customer);  // delete customer
		$this->assertInternalType('boolean', $delcust);  // confirm deletion
		$this->assertEquals(1, $delcust);  // confirm deletion
		$this->assertNotEquals(0, $delcust);  // confirm deletion
	  }
  }
  
}

 
