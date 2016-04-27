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
    $order = $odao->createOrder($orderData);
    $this->assertInstanceOf('Order',$order);
    $orderline = new OrderLine(array(
      "id_p" => 1,
      "quantite" => 0.4
    ));
    $orderline = $odao->addOrderLine($order,$orderline);
    $this->assertInternalType('int',$orderline->id_lc());
    $lineId = $orderline->id_lc();
    $orderline->setQuantite(2);
    $orderBis = $odao->changeOrderLine($order,$orderline);
    $this->assertEquals($order->id_c(),$orderBis->id_c());
    $order = $orderBis;
    $orderline = $order->getLine($lineId);
    $this->assertEquals($orderline->quantite(), 2);
    $order = $odao->delOrderLine($order,$lineId);
    $this->assertEquals(count($order->list_lc()), 1);

    $order = $odao->resetOpenOrder($order);
    $this->assertEquals(count($order->list_lc()), 0);

    $orders = $odao->getOrders();
    $order2 = $orders[0];
    $this->assertInstanceOf('Order',$order2);
    $this->assertInternalType('int',$order2->id_com());
    
  }
}
