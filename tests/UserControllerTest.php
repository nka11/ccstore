<?php
require_once './model/CustomerDAO.php';
class UserControllerTest extends PHPUnit_Framework_TestCase{
	public function testLoginActionInCaseUnknownUser(){
		$custdao = new CustomerDAO();
		
		// Case user does not exist
		$email = "unknownUser@email.test";
		$password = "testPassword";
		$customer = $custdao->getCustomerByEmail($email);
		$this->assertInternalType('boolean',$customer);
	}
	
	public function testLoginActionInCaseKnownUserAndPwsMatch(){
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
	
	public function testLoginActionInCaseKnownUserAndPwsNotMatch(){
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
	
	public function testLoginWithInvalidFormEmail(){
		$custdao = new CustomerDAO();
		$emails = array( null, "", "wrongformatAemail.com", "wrong@format")
		$password = "testPassword";
		
		
		
	}
	
	public function testPostActionInCaseNewCustomer(){
		$custdao = new CustomerDAO();
		
		// case of new inscription
		$newcustomer = new Customer(array(
			"email" => "new@email.test",
			"password" => "testPassword",
			"name" => "Testname",
			"firstname" => "Testfirstname",
			"address" => "1 test adress",
			"zip" => "77000",
			"town" => "Testcity",
			"phone" => "0123456789"
		));
		$realnewcustomer = $custdao->getCustomerByEmail($newcustomer->email());
		$this->assertinternalType('boolean', $realnewcustomer);
	}
}