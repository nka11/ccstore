<?php
require_once("./model/AbstractRestClient.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class ClientDAO extends AbstractRestClient {
  public function getClientsList() {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/thirdparty/list/customers?api_key=$this->api_key");
    $resp = $req->send();

  }

  public function getClientByEmail($email) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/byemail/$email?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    return $this->mapClient($resp->body);
  }

  public function getClientById($Id) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/$id?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    return $this->mapClient($resp->body);
  }
  public function updateClient($client) {
    if ($client->id_c() == null 
        || $client->id_c() <= 0) { // client must have an id
      return false;
    }
    $cldata = $this->mapDataClient($client);
    $req = $this->req();
    $req->sendJson();
    $req->body(json_encode($reqData));
    $req->uri("$this->api_url/customer/".$this->id_c()."?api_key=$this->api_key");
    $req->method("PUT");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $cdata = $this->mapDataContact($client);
    $req = $this->req();
    $req->uri("$this->api_url/contact?api_key=$this->api_key");
    $req->body(json_encode($cdata));
    $req->method("PUT");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    return $client;
  }
  public function deleteClient($client) {
    $req = $this->req();
    $req->sendJson();
    $req->uri("$this->api_url/customer/".$this->id_c()."?api_key=$this->api_key");
    $req->method("DELETE");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    return true;

  }
  public function createClient($client) {
    $existing = $this->getClientByEmail($client->email());
    // a client exist with that email, returning null
    if ($existing != null) {
      return false;
    }
    $req = $this->req();
    $req->sendJson();
    $reqData = $this->mapDataClient($client);
    $req->body(json_encode($reqData));
    $req->uri("$this->api_url/customer?api_key=$this->api_key");
    $req->method("POST");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    //echo "\ncreate client result : [".$res->body."]";
    $client->setId_c((int)$res->body);
    $cdata = $this->mapDataContact($client);
    $req = $this->req();
    $req->uri("$this->api_url/contact?api_key=$this->api_key");
    $req->body(json_encode($cdata));
    $req->method("POST");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    //echo "\ncreate client contact result : ".json_encode($res->body,JSON_PRETTY_PRINT);
    return $client;
  }

  public function mapDataContact($client) {
    $data = array(
      "socid" => json_encode($res->body),
      "socname" => $client->nom()." ".$client->prenom(),
      "address" => $client->adresse(),
      "zip" => $client->code_postal(),
      "town" => $client->ville(),
      "status" => "1",
      "email" => $client->email(),
      "mail" => $client->email(),
      "phone_perso" => $client->telephone(),
      "lastname" => $client->nom(),
      "firstname" => $client->prenom()
    );
    if ($client->id_contact() != null
      || $client->id_contact() > 0) {
      $data["id"] = $client->id_contact();
    }
    return (object)$data;
  }

  public function mapDataClient($client) {
    $data = array(
      "email" => $client->email(),
      "forme_juridique_code" => "19",
      "status" => "1",
      "tva_assuj" => "1",
      "typent_id" => "8",
      "typent_code" => "TE_PRIVATE",
      "client" => "1",
      "name" => $client->nom()." ".$client->prenom(),
      "lastname" => $client->nom(),
      "firstname" => $client->prenom(),
      "address" => $client->adresse(),
      "zip" => $client->code_postal(),
      "town" => $client->ville(),
      "departement" => $client->departement(),
      "phone" => $client->telephone()
    );
    if ($client->id_c() != null
      || $client->id_c() > 0) {
      $data["id"] = $client->id_c();
    }
    return (object)$data;
  }
  public function mapClient($data) {
    return new Client(array(
      "id_c" => (int)$data->id,
      "email" => $data->email,
      "nom" => $data->contacts[0]->lastname,
      "prenom" => $data->contacts[0]->firstname,
      "id_contact" => $data->contacts[0]->id,
      "adresse" => $data->address,
      "code_postal" => $data->zip,
      "departement" => $data->departement,
      "telephone" => $data->phone
    ));
  }
}
