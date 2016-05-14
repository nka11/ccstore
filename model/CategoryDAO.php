<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Category.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class CategoryDAO extends AbstractRestClient {
  public function getCategories() {
    $result = array();
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/category/list?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
			return false;
	}
    foreach ($resp->body as $data) {
      array_push($result, $this->_mapCategory($data));
    }
    return $result;
  }
  /**
   * @param id $id Id category a récupérer
   */
  public function getCategoryById($id) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/category/".$id."?api_key=$this->api_key");
    $resp = $req->send();
	 if ($resp->code == 404) {
			return false;
	}
    $category = $this->_mapCategory($resp->body);
    return $category;
  }
  
  public function getCategoryByLabel($label){
	$req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/category/".$label."?api_key=$this->api_key");
    $resp = $req->send();
	if ($resp->code == 404) {
			return false;
	}
	echo json_encode($resp->body,JSON_PRETTY_PRINT);exit();
    $category = $this->_mapCategory($resp->body);
    return $category;
  }
  
  private function _mapCategory($data) {
    $category = new Category(array(
      "id" => (int)$data->id,
      "ref" => $data->ref,
      "label" => $data->label,
      "description" => $data->description
    ));
    return $category;
  }

}
