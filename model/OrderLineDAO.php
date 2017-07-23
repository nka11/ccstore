<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/OrderLine.class.php");
include_once './vendor/autoload.php';

class OrderLineDAO extends AbstractClient {
	public function getOrderLinesByOrder($order_id){
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM order_line WHERE fk_order=".$order_id);
		$req->execute();
		 foreach($req->errorInfo() as $e)
		{
			if($e != 0) echo $e;
		}
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapOrderLine($data);
			}	
		}
		return $result;
	}
	public function getOrderLineById($id){}
	public function createOrderLine(OrderLine $orderline){
		 $req=$this->pdo_db->prepare('INSERT INTO order_line SET fk_order=:fk_order, fk_product=:fk_product, amount=:amount, ol_value=:value');
		 $req->bindValue(':fk_order', $orderline->fk_order(), PDO::PARAM_INT);
		 $req->bindValue(':fk_product', $orderline->fk_product(), PDO::PARAM_INT);
		 $req->bindValue(':amount', $orderline->amount(), PDO::PARAM_INT);
		 $req->bindValue(':value', $orderline->value());
		 $req->execute();
		  foreach($req->errorInfo() as $e)
		{
			if($e != 0) return false;
		}
		return true;
	}
	public function updateOrderLine(Order $order){}
	public function deleteOrderLine(Order $order){}
	public function mapOrderLine($data){
		$orderline = new OrderLine(array(
			"id"=>(int)$data['rowid'],
			"fk_order"=>(int)$data['fk_order'],
			"fk_product"=>(int)$data['fk_product'],
			"amount"=>$data['amount'],
			"value"=>$data['ol_value']
	  ));
	  return $orderline;
  }
}