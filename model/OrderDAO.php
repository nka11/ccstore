<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Order.class.php");
require_once("./model/class/OrderLine.class.php");
require_once("./model/ClientDAO.php");
require_once("./model/ProduitDAO.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class OrderDAO extends AbstractRestClient {

  private $client = NULL;
  private $cldao = NULL;
  private $pdao = NULL;
  private $client_key;

  public function __construct($client = NULL) {
    parent::__construct();
    $this->cldao = new ClientDAO($this->api_key);
    $this->pdao = new ProduitDAO($this->api_key);
    if ($client == NULL) {
      return;
    }
    $this->_authenticateClient($client);
  }

  private function _authenticateClient($client) {
    if ($client->id_user() <= 0) { // should never happend
      throw new Exception("Invalid client object");
    }
    $this->client = $client;
    $this->cldao->login($client);
    $this->client_key = $client->api_key();
  }

  public function getOrderById($oid) {
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/order/$oid?api_key=$this->api_key");
    $res = $req->send();
    if ($res->code != 200) {
      return false;
    }
    $order = $this->mapOrder($res->body);
    return $order;
  }
  public function getOrders() {
    $result = array();
    $req = $this->req();
    $req->method("GET");
    $req->uri("$this->api_url/order/list?api_key=$this->api_key");
    $res = $req->send();
    if ($res->code == 200) {
      foreach ($res->body as $orderdata) {
        array_push($result, $this->mapOrder($orderdata));
        return $result;
      }
    }
    return false;
  }

  /**
   * Creates a draft order (brouillon, panier) for a client
   * Anonymous basked cannot be created with dolibarr
   *
   */
  public function createOrder($commande,$client=NULL) {
    if ($this->client == NULL && $client == NULL) {
      // pas de client, pas de chocolat
      throw new Exception("Client must be specified");
    }
    if ($client == NULL) {
      $client = $this->client;
    } else {
      $this->_authenticateClient($client);
    }
    $reqdata = $this->mapDataOrder($commande,$this->client);
    $req = $this->req();
    $req->sendJson();
    $req->method("POST");
    $req->body(json_encode($reqdata));
    echo json_encode($reqdata, JSON_PRETTY_PRINT);
    $req->uri("$this->api_url/order?api_key=$this->api_key");
    $res = $req->send();
    if ($res->code != 200) {
      echo json_encode($res->body, JSON_PRETTY_PRINT);
    }
    echo json_encode($res->body, JSON_PRETTY_PRINT);
    $commande->setId_com((int)$res->body);
    return $commande;
  }


  public function updateOrder($commande,$client=NULL) {
    if ($this->client == NULL && $client == NULL) {
      // pas de client, pas de chocolat
      throw new Exception("Client must be specified");
    }
    if ($commande->id_com() == NULL) {
      throw new Exception("Can't update unreferenced order");
    }
    if ($client == NULL) {
      $client = $this->client;
    } else {
      $this->_authenticateClient($client);
    }
    $reqdata = $this->mapDataOrder($commande,$this->client);
    $req = $this->req();
    $req->sendJson();
    $req->method("PUT");
    $req->body(json_encode($reqdata));
    echo json_encode($reqdata, JSON_PRETTY_PRINT);
    $req->uri("$this->api_url/order/".$commande->id_com()."?api_key=$this->api_key");
    $res = $req->send();
    if ($res->code != 200) {
      echo json_encode($res->body, JSON_PRETTY_PRINT);
    }
      echo json_encode($res->body, JSON_PRETTY_PRINT);
    return $commande;

  }
  public function resetOpenOrder($client) {

  }

  public function mapDataOrder($commande,$client) {
    $data = array(
      "socid" => $commande->id_c(),
      "date_commande" => $commande->date_crea_com(),
      "shipping_method_id" => $commande->mode_liv(),
      //"date_livraison" => $commande->date_liv(),
      "mode_reglement_id" => (int)$commande->mode_paiement(),
      "cond_reglement_id" => (int)$commande->cond_paiement(),
      "statut" => $commande->statut(),
      "lines" => array()
    );
    if ($commande->id_com() != null
      || $client->id_c() > 0) {
      $data["id"] = $commande->id_com();
    }
    if ($commande->refcom() != null) {
      $data["ref"] = $commande->refcom();
    }
    foreach($commande->list_lc() as $line) {
      array_push($data["lines"], $this->mapDataLineOrder($line));
    }
    return (object)$data;
  }

  public function mapDataLineOrder($orderline) {
    $product = $this->pdao->getProduitById($orderline->id_p());
    $data = array(
      "fk_product" => $orderline->id_p(),
      //"fk_commande" => $orderline->id_com(),
      "qty" => $orderline->quantite(),
      "ref" => $product->ref(),
      "product_ref" => $product->ref(),
      "libelle" => $product->titre(),
      "label" => $product->titre(),
      "desc" => $product->description(),
      "subprice" => $product->prix_vente(),
      "tva_tx" => $product->tva()
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
      "refcom" => $data->ref,
      "id_c" => (int)$data->socid,
      "date_crea_com" => $data->date,
      "mode_liv" => (int)$data->shipping_method_id,
      "date_liv" => $data->date_livraison,
      "mode_paiement" => $data->mode_reglement_id,
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
