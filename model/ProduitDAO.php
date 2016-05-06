<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Product.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class ProduitDAO extends AbstractRestClient {
  public function getProduits() {
    $result = array();
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/product/list?api_key=$this->api_key");
    $resp = $req->send();
    //echo json_encode($resp->body,JSON_PRETTY_PRINT);
    foreach ($resp->body as $data) {
      array_push($result, $this->_mapProduct($data));
    }
    return $result;
  }
  /**
   * @param id $id Id produit a récupérer
   */
  public function getProduitById($id) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/product/".$id."?api_key=$this->api_key");
    $res = $req->send();
    $produit = $this->_mapProduct($res->body);
    return $produit;
  }
  /**
   * @param Product $produit Objet Produit a completer
   */
  public function getProduitCategories($produit) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/product/".$produit->id_p()."/categories?api_key=$this->api_key");
    $resp = $req->send();
    $produit->setCategories($resp->body);
    return $produit;
  }

  public function getProduitsByCategory($category) {
    $result = array();
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/product/list/category/$category?api_key=$this->api_key");
    $resp = $req->send();
    foreach ($resp->body as $data) {
      array_push($result, $this->_mapProduct($data));
    }
    return $result;
  }



  private function _mapProduct($data) {
    $product = new Product(array(
      "id_p" => (int)$data->id,
      "ref" => $data->ref,
      "titre" => $data->label,
      "description" => $data->description,
      "tva" => (float)$data->tva_tx,
      "prix_vente" => (float)$data->price_ttc
    ));
    return $product;
  }

  private function _getProduitCategories($product) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/product/"
      .$product->id_p()
      ."/categories/?api_key=$this->api_key");
    $resp = $req->send();
    
  }

}
