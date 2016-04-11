<?php

require_once("./model/ClientDAO.php");
require_once("./model/class/Client.class.php");
class Client2DAOTest extends PHPUnit_Framework_TestCase
{
  public function testCreateClient() {
    $clientData = new Client(array(
      "nom" => "test deux bis",
      "prenom" => "test prenom bis",
      "email" => "test3@email.test",
      "mdp" => "testPassword"
    ));
    $cldao = new ClientDAO();
    $client = $cldao->createClient($clientData);
    $this->assertNotInternalType('boolean',$client);
    $this->assertInternalType('int',$client->id_c());
    $this->assertInternalType('int',$client->id_user());
    $newClient = $cldao->getClientById($client->id_c());
    $this->assertEquals($client->id_c(),$newClient->id_c());
    $this->assertEquals($client->nom(),$newClient->nom());
    $this->assertEquals($client->email(),$newClient->email());
    $client->setAdresse("123 arava");
    $client->setCode_postal("12345");
    $client->setVille("Paris");
    $cldao->updateClient($client);
    $newClient = $cldao->getClientByEmail($client->email());
    $this->assertEquals($client->adresse(),$newClient->adresse());
    $this->assertEquals($client->ville(),$newClient->ville());
    $this->assertEquals($client->code_postal(),$newClient->code_postal());
    $newClient->setMdp("testPassword");
    $newClient = $cldao->login($newClient);
    $this->assertInternalType('string',$newClient->api_key());
    $delres = $cldao->deleteClient($newClient);
    $this->assertInternalType('boolean',$delres);
    $this->assertTrue($delres);
    $newClient = $cldao->getClientById($client->id_c());
    $this->assertInternalType('boolean',$newClient);
    $this->assertFalse($newClient);

  }
}

