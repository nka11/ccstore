<?php

require_once("./model/OrderDAO.php");
include_once './vendor/autoload.php';
include_once './conf/dolirest.cnf.default.php';
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
     $res = $req->send();
     if ($res->code != 200) {
       echo json_encode($res->body,JSON_PRETTY_PRINT);
     }
     $this->api_key = $res->body->success->token;
		 $req = Request::init();
     $req->mime("application/json");
     $req->method("GET");
     $req->uri("$this->api_url/product/2?api_key=$this->api_key");
		 $res = $req->send();
		 echo json_encode($res->body, JSON_PRETTY_PRINT);
		 $req = Request::init();
     $req->mime("application/json");
     $req->method("GET");
     $req->uri("$this->api_url/order/2?api_key=$this->api_key");
		 $resp = $req->send();
		 echo json_encode($resp->body, JSON_PRETTY_PRINT);
  }
}
