<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Payment.class.php");
include_once './vendor/autoload.php';

class PaymentDAO extends AbstractClient {
	public function getPaymentByOrder($order_id){
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."order_payment WHERE fk_order=".$order_id);
		$req->execute();
		 foreach($req->errorInfo() as $e)
		{
			if($e != 0) echo $e;
		}
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapPayment($data);
			}	
		}
		return $result;
	}
	public function getPaymentById($id){}
	public function createPayment(Payment $payment){
		 $req=$this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."order_payment SET fk_order=:fk_order, day=NOW(), means=:means");
		 $req->bindValue(':fk_order', $payment->fk_order(), PDO::PARAM_INT);
		 $req->bindValue(':means', $payment->means());
		 $req->execute();
		  foreach($req->errorInfo() as $e)
		{
			if($e != 0) return false;
		}
		return true;
	}
	public function updatePayment(Payment $payment){
		$req=$this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."order_payment UPDATE fk_order=:fk_order, day=NOW(), means=:means WHERE id=:id");
		 $req->bindValue(':id', $payment->id(), PDO::PARAM_INT);
		 $req->bindValue(':fk_order', $payment->fk_order(), PDO::PARAM_INT);
		 $req->bindValue(':means', $payment->means());
		 $req->execute();
		  foreach($req->errorInfo() as $e)
		{
			if($e != 0) return false;
		}
		return true;
	}
	public function deletePayment(Payment $payment){}
	public function mapPayment($data){
		$payment = new Payment(array(
			"id"=>(int)$data['rowid'],
			"fk_order"=>(int)$data['fk_order'],
			"day"=> $data['day'],
			"means"=>$data['means']
	  ));
	  return $payment;
  }
}