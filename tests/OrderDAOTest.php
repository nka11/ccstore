<?php

require_once("./model/OrderDAO.php");
require_once("./model/ClientDAO.php");
require_once("./model/ProduitDAO.php");
require_once("./model/class/Order.class.php");
require_once("./model/class/Client.class.php");
class OrderDAOTest extends PHPUnit_Framework_TestCase
{
  public function testClientOrder() {
    $clientData = new Client(array(
      "nom" => "test 5",
      "prenom" => "test prenom",
      "email" => "test5@email.test",
      "mdp" => "testPassword"
    ));
    $cldao = new ClientDAO();
    $client = $cldao->createClient($clientData);
    $client = $cldao->getClientByEmail("test5@email.test");
    $client->setMdp("testPassword");
    $odao = new OrderDAO($client);
    $orderData = new Order(array(
      "id_c" => $client->id_c(),
      "mode_liv" => 2, // Livraison a domicile
      "cond_paiement" => 7, // Paiement a livraison
      "mode_paiement" =>7 // paiement par cheque
    ));
    $orderline = new OrderLine(array(
      "id_p" => 3,
      "quantite" => 1
    ));
    $orderData->setList_lc([$orderline]);
    $oid = $odao->createOrder($orderData);
    $this->assertInternalType('int',$oid);
    $order = $odao->getOrderById($oid->id_com());
    $orderline = new OrderLine(array(
      "id_p" => 1,
      "quantite" => 0.4
    ));
    $order = $odao->addOrderLine($order,$orderline);
    $orders = $odao->getOrders();
    $order = $orders[0];
    $this->assertInstanceOf('Order',$order);
    $this->assertInternalType('int',$order->id_com());
  }
}
