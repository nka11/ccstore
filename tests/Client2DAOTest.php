<?php

require_once("./model/CustomerDAO.php");
require_once("./model/class/Customer.class.php");
class Customer2DAOTest extends PHPUnit_Framework_TestCase
{
  public function testCreateCustomer() {
    $clientData = new Customer(array(
      "name" => "test deux bis",
      "firstname" => "test prenom bis",
      "email" => "test3@email.test",
      "password" => "testPassword"
    ));
    $cldao = new CustomerDAO();
    $client = $cldao->createCustomer($clientData);
    $this->assertNotInternalType('boolean',$client);
    $this->assertInternalType('int',$client->id_c());
    $this->assertInternalType('int',$client->id_user());
    $newCustomer = $cldao->getCustomerById($client->id_c());
    $this->assertEquals($client->id_c(),$newCustomer->id_c());
    $this->assertEquals($client->nom(),$newCustomer->nom());
    $this->assertEquals($client->email(),$newCustomer->email());
    $client->setAdresse("123 arava");
    $client->setCode_postal("12345");
    $client->setVille("Paris");
    $cldao->updateCustomer($client);
    $newCustomer = $cldao->getCustomerByEmail($client->email());
    $this->assertEquals($client->adresse(),$newCustomer->adresse());
    $this->assertEquals($client->ville(),$newCustomer->ville());
    $this->assertEquals($client->code_postal(),$newCustomer->code_postal());
    $newCustomer->setMdp("testPassword");
    $newCustomer = $cldao->login($newCustomer);
    $this->assertInternalType('string',$newCustomer->api_key());
    $delres = $cldao->deleteCustomer($newCustomer);
    $this->assertInternalType('boolean',$delres);
    $this->assertTrue($delres);
    $newCustomer = $cldao->getCustomerById($client->id_c());
    $this->assertInternalType('boolean',$newCustomer);
    $this->assertFalse($newCustomer);

  }
}

