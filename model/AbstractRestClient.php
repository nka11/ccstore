<?php
include_once './vendor/autoload.php';
include './conf/dolirest.cnf.default.php';
use \nategood\httpful;
use \Httpful\Request;
class AbstractRestClient {
  public $api_key;
  public $api_url;

  public function __construct() {
    global $dolibarr_api_url, $dolibarr_user_login, $dolibarr_user_password;
    $req = Request::init();
    $req->mime("application/json");
    $req->method("GET");
    $this->api_url = $dolibarr_api_url;
    $req->uri("$this->api_url/login?login=$dolibarr_user_login&password=$dolibarr_user_password");
    $resp = $req->send();
    $this->api_key = $resp->body->success->token;
//    echo $this->api_key;
  }

  public function req() {
    $req = Request::init();
    $req->mime("application/json");
    return $req;
  }
}
