<?php
include_once './vendor/autoload.php';
include './conf/dolirest.cnf.default.php';
use \nategood\httpful;
use \Httpful\Request;
class AbstractRestClient {
  public $api_key;
  public $api_url;

  public function __construct($api_key=NULL) {
    global $dolibarr_api_url, $dolibarr_user_login, $dolibarr_user_password;
    $this->api_url = $dolibarr_api_url;
    if ($api_key != NULL) { $this->api_key = $api_key; return;}
    $req = Request::init();
    $req->mime("application/json");
    $req->method("GET");
    $req->uri("$this->api_url/login?login=$dolibarr_user_login&password=$dolibarr_user_password");
    $resp = $req->send();
    if ($resp->code != 200) {
      throw new RestException("API not initialized");
    }
    $this->api_key = $resp->body->success->token;
//    echo $this->api_key;
  }

  public function req() {
    $req = Request::init();
    $req->mime("application/json");
    return $req;
  }
}

class RestException extends Exception {

}
