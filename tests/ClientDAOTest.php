<?php

require_once("./model/ClientDAO.php");
require_once("./model/class/Client.class.php");
class ClientDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

  public function testGetClientsList() {
    $cldao = new ClientDAO();
    $cldao->getClientsList();
  }
  public function testGetUnexistingClientByEmail() {
    $cldao = new ClientDAO();
    $client = $cldao->getClientByEmail("noclient@testDomain.com");
    $this->assertInternalType('boolean',$client);
    $this->assertFalse($client);
  }
  public function testGetClientAdherentByEmail() {
    $cldao = new ClientDAO();
    $client = $cldao->getClientByEmail("testClientAdherent@testDomain.com");
  }
  /*
  public function testGetClientByEmail() {
    $cldao = new ClientDAO();
    $client = $cldao->getClientByEmail("testclient2@testdomain.com");
  } */
  public function testCreateClient() {
    $clientData = new Client(array(
      "nom" => "test un",
      "prenom" => "test prenom",
      "email" => "test@email.test",
      "password" => "testPassword"
    ));
    $cldao = new ClientDAO();
    $client = $cldao->createClient($clientData);
    $this->assertNotInternalType('boolean',$client);
    $this->assertInternalType('int',$client->id_c());
    $newClient = $cldao->getClientById($client->id_c());
    $this->assertEquals($client->id_c(),$newClient->id_c());
    $this->assertEquals($client->nom(),$newClient->nom());
    $this->assertEquals($client->email(),$newClient->email());
    $client->setAdresse("123 arava");
    $client->setCode_postal("12345");
    $client->setVille("Paris");
    $cldao->updateClient($client);
    $newClient = $cldao->getClientById($client->id_c());
    $this->assertEquals($client->adresse(),$newClient->adresse());
    $this->assertEquals($client->ville(),$newClient->ville());
    $this->assertEquals($client->code_postal(),$newClient->code_postal());
    $delres = $cldao->deleteClient($newClient);
    $this->assertInternalType('boolean',$delres);
    $this->assertTrue($delres);
    $newClient = $cldao->getClientById($client->id_c());
    $this->assertInternalType('boolean',$newClient);
    $this->assertFalse($newClient);
  }
}

