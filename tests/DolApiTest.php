<?php

require("./model/OrderDAO.php");
include_once './vendor/autoload.php';
include './conf/dolirest.cnf.default.php';
use \nategood\httpful;
use \Httpful\Request;
class DolApiTest extends PHPUnit_Framework_TestCase
{
   public $api_key;
  public $api_url;

   public function testGetAccount() {
     global $dolibarr_api_url, $dolibarr_user_login, $dolibarr_user_password;
     $req = Request::init();
     $req->mime("application/json");
     $req->method("GET");
     $this->api_url = $dolibarr_api_url;
     $req->uri("$this->api_url/login?login=$dolibarr_user_login&password=$dolibarr_user_password");
     $resp = $req->send();
     $this->api_key = $resp->body->success->token;
		 $req = Request::init();
     $req->mime("application/json");
     $req->method("GET");
     $req->uri("$this->api_url/account/2?api_key=$this->api_key");
		 $resp = $req->send();
		 echo json_encode($resp->body, JSON_PRETTY_PRINT);
  }
}
