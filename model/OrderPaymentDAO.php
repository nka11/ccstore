<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/OrderPayment.class.php");
include_once './vendor/autoload.php';

class OrderPaymentDAO extends AbstractClient {
	public function getOrderPaymentByOrder($order_id){
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."order_payment WHERE fk_order=".$order_id);
		$req->execute();
		 foreach($req->errorInfo() as $e)
		{
			if($e != 0) echo $e;
		}
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapOrderPayment($data);
			}	
		}
		return $result;
	}
	public function getOrderPaymentById($id){}
	public function createOrderPayment(OrderPayment $payment){
		 $req=$this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."order_payment SET fk_order=:fk_order, day=NOW(), payment_method=:method");
		 $req->bindValue(':fk_order', $payment->fk_order(), PDO::PARAM_INT);
		 $req->bindValue(':means', $payment->method());
		 $req->execute();
		  foreach($req->errorInfo() as $e)
		{
			if($e != 0) return false;
		}
		return true;
	}
	public function updateOrderPayment(OrderPayment $payment){
		$req=$this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."order_payment UPDATE fk_order=:fk_order, day=NOW(), payment_method=:method WHERE id=:id");
		 $req->bindValue(':id', $payment->id(), PDO::PARAM_INT);
		 $req->bindValue(':fk_order', $payment->fk_order(), PDO::PARAM_INT);
		 $req->bindValue(':method', $payment->method());
		 $req->execute();
		  foreach($req->errorInfo() as $e)
		{
			if($e != 0) return false;
		}
		return true;
	}
	public function deleteOrderPayment(OrderPayment $payment){}
	
	public function mapOrderPayment($data){
		$payment = new OrderPayment(array(
			"id"=>(int)$data['rowid'],
			"fk_order"=>(int)$data['fk_order'],
			"day"=> $data['day'],
			"method"=>$data['payment_method']
	  ));
	  return $payment;
  }
}