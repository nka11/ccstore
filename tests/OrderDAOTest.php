<?php

require("./model/OrderDAO.php");
class OrderDAOTest extends PHPUnit_Framework_TestCase
{
  public function testGetOrders() {
    $odao = new OrderDAO();
    $orders = $odao->getOrders();
    $order = $orders[0];
    $this->assertInstanceOf('Order',$order);
    $this->assertInternalType('int',$order->id_com());
  }
}
