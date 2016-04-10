<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Order.class.php");
require_once("./model/class/OrderLine.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class OrderDAO extends AbstractRestClient {
  public function getOrders() {
    $result = array();
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/order/list?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 200) {
      foreach ($resp->body as $orderdata) {
        array_push($result, $this->mapOrder($orderdata));
      }
      return $result;
    }
    
  }
  public function getOrdersClient($client) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/thirdparty/list/customers?api_key=$this->api_key");
    $resp = $req->send();

  }

  public function getOpenOrder($client) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/customer/byemail/$email?api_key=$this->api_key");
    $resp = $req->send();
    if ($resp->code == 404) {
      return false;
    }
    return $this->mapOrder($resp->body);
  }

  public function updateOrder($client,$commande) {
    if ($client->id_c() == null 
        || $client->id_c() <= 0) { // client must have an id
      return false;
    }
    $cldata = $this->mapDataOrder($client);
    $req = $this->req();
    $req->sendJson();
    $req->body(json_encode($cldata));
    $req->uri("$this->api_url/customer/".$client->id_c()."?api_key=$this->api_key");
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
  public function resetOpenOrder($client) {
    $req = $this->req();
    $req->sendJson();
    $req->uri("$this->api_url/customer/".$client->id_c()."?api_key=$this->api_key");
    $req->method("DELETE");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    return true;

  }

  public function mapDataOrder($commande) {
    $data = array(
      "socid" => $commande->id_c(),
      "date_commande" => $commande->date_crea_com(),
      "shipping_method_id" => $commande->mode_liv(),
      "date_livraison" => $commande->date_liv(),
      "mode_reglement_code" => $commande->mode_paiement(),
      "statut" => $commande->statut()
    );
    if ($commande->id_com() != null
      || $client->id_com() > 0) {
      $data["id"] = $commande->id_com();
    }
    return (object)$data;
  }

  public function mapDataLineOrder($orderline) {
    $data = array(
      "fk_product" => $orderline->id_p(),
      "fk_commande" => $orderline->id_com(),
      "qty" => $orderline->quantite()
    );
    if ($orderline->id_lc() != null
      || $orderline->id_lc() > 0) {
      $data["id"] = $orderline->id_lc();
    }

    return (object)$data;
  }

  public function mapOrder($data) {
    $order = new Order(array(
      "id_com" => (int)$data->id,
      "id_c" => (int)$data->socid,
      "date_crea_com" => $data->date,
      "mode_liv" => $data->shipping_method_id,
      "date_liv" => $data->date_livraison,
      "mode_paiement" => $data->mode_reglement_code,
      "statut" => $data->statut
    ));
    $lines = array();
    foreach ($data->lines as $line) {
      $oline = new OrderLine(array(
        "id_lc" => $line->id,
        "id_p" => $line->fk_product,
        "id_com" => $line->fk_commande,
        "quantite" => $line->qty
      ));
      array_push($lines,$oline);
    }
    $order->setList_lc($lines);
    return $order;
  }
}
