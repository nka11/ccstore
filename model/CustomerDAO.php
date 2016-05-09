<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Customer.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class CustomerDAO extends AbstractRestClient {
  public function getCustomers() {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/thirdparty/list/customers?api_key=$this->api_key");
    $resp = $req->send();

  }

  public function getCustomerByEmail($email) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/byemail/$email?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    $custdata = $resp->body;
    $custid = (int)$custdata->id;
    $req = $this->req();
    $req->uri("$this->api_url/customer/$custid/contacts?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    $custdata->contacts = $resp->body;
    return $this->mapCustomer($custdata);
  }

  public function getCustomerById($custid) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/$custid?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    $custdata = $resp->body;
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/$custid/contacts?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    $cldata->contacts = $resp->body;
    return $this->mapCustomer($custdata);
  }
  public function updateCustomer(Customer $customer) {
    if ($customer->id_c() == null 
        || $customer->id_c() <= 0) { // client must have an id
      return false;
    }
    $custdata = $this->mapDataCustomer($customer);
    $req = $this->req();
    $req->sendJson();
    $req->body(json_encode($custdata));
    $req->uri("$this->api_url/customer/".$customer->id_c()."?api_key=$this->api_key");
    $req->method("PUT");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $cdata = $this->mapDataContact($customer);
    $req = $this->req();
    $req->uri("$this->api_url/contact?api_key=$this->api_key");
    $req->body(json_encode($cdata));
    $req->method("PUT");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    return $customer;
  }
  public function deleteCustomer(Customer $customer) {
    $req = $this->req();
    $req->sendJson();
    $req->uri("$this->api_url/customer/".$customer->id_c()."?api_key=$this->api_key");
    $req->method("DELETE");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $req = $this->req();
    $req->uri("$this->api_url/user/".$customer->id_user()."?api_key=$this->api_key");
    $req->method("DELETE");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }

    return true;

  }

  public function login(Customer $customer) {
    $req = $this->req();
    $req->uri("$this->api_url/login?login=".$customer->email()."&password=".$customer->mdp());
    $req->method("GET");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $customer->setApi_key($res->body->success->token);
    return $customer;
  }

  public function createCustomer(Customer $customer) {
    global $dolibarr_web_customer_catid,
           $dolibarr_clientadherent_catid,
           $dolibarr_web_customer_groupid;
    $existing = $this->getCustomerByEmail($customer->email());
    // a client exist with that email, returning null
    if ($existing != false) {
      return false;
    }
    $req = $this->req();
    $req->sendJson();
    $reqData = $this->mapDataCustomer($customer);
    $req->body(json_encode($reqData));
    $req->uri("$this->api_url/customer?api_key=$this->api_key");
    $req->method("POST");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $customer->setId_c((int)$res->body);
    $cdata = $this->mapDataContact($customer);
    $req = $this->req();
    $req->uri("$this->api_url/contact?api_key=$this->api_key");
    $req->body(json_encode($cdata));
    $req->method("POST");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $customer->setId_contact($res->body);
    $req = $this->req();
    $req->uri("$this->api_url/customer/".$customer->id_c()
      ."/addCategory/$dolibarr_web_customer_catid?api_key=$this->api_key");
    $req->method("GET");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }

    $req = $this->req();
    $req->uri("$this->api_url/contact/".$customer->id_contact()."/createUser?api_key=$this->api_key");
    $req->body("{\"login\":\"".$customer->email()."\",\"password\":\"".$customer->password()."\"}");
    $req->method("POST");
    $res = $req->send();
    if ($res->code != 200) {
      //echo json_encode($res->body,JSON_PRETTY_PRINT);
      return false;
    }
    $customer->setId_user($res->body);

    $req = $this->req();
    $req->uri("$this->api_url/user/".$customer->id_user()."/setGroup/"
      ."$dolibarr_web_customer_groupid?api_key=$this->api_key");
    $req->method("GET");
    $res = $req->send();
    if ($res->code != 200) {
      echo json_encode($res->body,JSON_PRETTY_PRINT);
      return false;
    }
    return $customer;
  }

  public function mapDataContact($customer) {
    $data = array(
      "socid" => json_encode($customer->id_c()),
      "socname" => $customer->name()." ".$customer->firstname(),
      "address" => $customer->address(),
      "zip" => $customer->zip(),
      "town" => $customer->town(),
      "status" => "1",
      "email" => $customer->email(),
      "mail" => $customer->email(),
      "phone_perso" => $customer->phone(),
      "lastname" => $customer->name(),
      "firstname" => $customer->firstname()
    );
    if ($customer->id_contact() != null
      || $customer->id_contact() > 0) {
      $data["id"] = $customer->id_contact();
    }
    return (object)$data;
  }

  public function mapDataCustomer($customer) {
    $data = array(
      "email" => $customer->email(),
      "forme_juridique_code" => "19",
      "status" => "1",
      "tva_assuj" => "1",
      "typent_id" => "8",
      "typent_code" => "TE_PRIVATE",
      "client" => "1",
      "name" => $customer->name()." ".$customer->firstname(),
      "lastname" => $customer->name(),
      "firstname" => $customer->firstname(),
      "address" => $customer->address(),
      "zip" => $customer->zip(),
      "town" => $customer->town(),
      "phone" => $customer->phone()
    );
    if ($customer->id_c() != null
      || $customer->id_c() > 0) {
      $data["id"] = $customer->id_c();
    }
    return (object)$data;
  }
  public function mapCustomer($data) {
    $customer = new Customer(array(
      "id_c" => (int)$data->id,
      "email" => $data->email,
      "name" => $data->name,
      "address" => $data->address,
      "zip" => $data->zip,
      "phone" => $data->phone,
      "town" => $data->town
    ));
    if (array_key_exists("id",$data)) {
      $customer->setId_c((int)$data->id);
    }
    if (array_key_exists("contacts",$data)) {
      $customer->setFirstname($data->contacts[0]->firstname);
      $customer->setName($data->contacts[0]->lastname);
      $customer->setId_contact((int)$data->contacts[0]->id);
      $customer->setId_user((int)$data->contacts[0]->user_id);
    }
    return $customer;
  }
}
