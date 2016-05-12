<?php

require_once("./model/OrderDAO.php");
require_once("./model/CustomerDAO.php");
require_once("./model/ProductDAO.php");
require_once("./model/class/Order.class.php");
require_once("./model/class/Customer.class.php");
class OrderDAOTest extends PHPUnit_Framework_TestCase
{
  public function testCustomerOrder() {
    $custData = new Customer(array(
      "name" => "test 5",
      "firstname" => "test prenom",
      "email" => "test5@email.test",
      "password" => "testPassword"
    ));
    $custdao = new CustomerDAO();
    $customer = $custdao->createCustomer($custData);
    $customer = $custdao->getCustomerByEmail("test5@email.test");
    $customer->setPassword("testPassword");
    $odao = new OrderDAO($customer);
    $orderData = new Order(array(
      "id_c" => $customer->id_c(),
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
