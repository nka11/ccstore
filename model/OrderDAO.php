<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Order.class.php");
require_once("./model/class/OrderPayment.class.php");
require_once("./model/class/Customer.class.php");
include_once './vendor/autoload.php';

class OrderDAO extends AbstractClient {
	public function getOrders($customer_type=NULL){
		$result=array();
		$string_type= ($customer_type!=NULL)
					?	" WHERE customer_type='".$customer_type."'"
					:	"";
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."privateorder".$string_type." ORDER BY order_date DESC");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[$data['ref']] = $this->mapOrder($data);
			}
		}
		return $result;
	}
	public function getOrderById($id){
		$order=null;
		$req= $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."privateorder WHERE rowid=".$id);
		$req->execute();
		
		if($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$order = $this->mapOrder($data);
			}
			return $order;
		}else{
			return false;
		}
	}
	public function getOrderByRef($ref){
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."privateorder WHERE ref=:ref");
		$req->bindValue(':ref', $ref);
		$req->execute();
		
		if ($req->rowCount() == 1){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$order = $this->mapOrder($data);
			}
			return $order;
		}else{
			return false;
		}	
	}
	public function getCurrentOrdersByUser($user, $delivery_week){
		$orders=array();
		$req_string= "SELECT * FROM ".$tb_prefix."privateorder WHERE fk_customer=".$user->id();
		$req_string.= " AND delivery_week=:delivery_week";
		$req_string.= " ORDER BY order_date DESC";
		$req = $this->pdo_db->prepare($req_string);
		$req->bindValue(':delivery_week', $delivery_week, PDO::PARAM_INT);
		$req->execute();
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$orders[] = $this->mapOrder($data);
			}
		return $orders;
	}
	public function getOrdersByUser($user, $status=null){
		$orders=array();
		$req_string= "SELECT * FROM ".$tb_prefix."privateorder WHERE fk_customer=".$user->id();
		$req_string.= ($status!=null)
							?	" AND status= '$status'"
							:	"";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$orders[] = $this->mapOrder($data);
			}
			if(count($orders) == 1 && $status == 'Pending') return $orders[0];
			else return $orders;
	}
	public function getOrdersByCustomer($customer, $status=null){
		$orders=array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."privateorder WHERE fk_customer=".$customer->id()." AND customer_type='".get_class($customer)."'";
		$req_string.= ($status!=null)
							?	" AND status= '$status'"
							:	"";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$orders[] = $this->mapOrder($data);
			}
			if(count($orders) == 1 && $status == 'Pending') return $orders[0];
			else return $orders;
	}
	public function createOrder(Order $order){
		 $req=$this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."privateorder SET fk_customer=:fk_customer, customer_type=:customer_type, ref=:ref, total_amount=:total_amount, delivery_address=:delivery_address, delivery_zip=:delivery_zip, delivery_town=:delivery_town, delivery_instructions=:delivery_instructions, delivery_week=:delivery_week, delivery_cost= :delivery_cost, delivery_date=:delivery_date, origin=:origin, status=:status, order_date=NOW()");
		 $req->bindValue(':fk_customer', $order->fk_customer(), PDO::PARAM_INT);
		 $req->bindValue(':customer_type', $order->customer_type());
		 $req->bindValue(':ref', $order->ref());
		 $req->bindValue(':total_amount', $order->total_amount());
		 $req->bindValue(':delivery_address', $order->delivery_address());
		 $req->bindValue(':delivery_zip', $order->delivery_zip());
		 $req->bindValue(':delivery_town', $order->delivery_town());
		 $req->bindValue(':delivery_date', $order->delivery_date());
		 $req->bindValue(':delivery_week', $order->delivery_week());
		 $req->bindValue(':delivery_instructions', $order->delivery_instructions());
		 $req->bindValue(':delivery_cost', $order->delivery_cost(), PDO::PARAM_INT);
		 $req->bindValue(':origin', $order->origin());
		 $req->bindValue(':status', $order->status());
		 $req->execute();
		 
		 foreach($req->errorInfo() as $e)
		{
			if($e != 0) echo $e;
		}
		$confirm = $this->getOrderByRef($order->ref());
			if($confirm != false){
				return $confirm;
			}
			else{
				return false;
			}
	}
	public function updateOrder(Order $order){
		 $req=$this->pdo_db->prepare("UPDATE ".$this->tb_prefix."privateorder SET total_amount=:total_amount, delivery_address=:delivery_address, delivery_zip=:delivery_zip, delivery_town=:delivery_town, delivery_instructions=:delivery_instructions, delivery_week=:delivery_week, delivery_date=:delivery_date, origin=:origin, status=:status WHERE ref=:ref");
		 $req->bindValue(':ref', $order->ref());
		 $req->bindValue(':total_amount', $order->total_amount());
		 $req->bindValue(':delivery_address', $order->delivery_address());
		 $req->bindValue(':delivery_zip', $order->delivery_zip());
		 $req->bindValue(':delivery_town', $order->delivery_town());
		 $req->bindValue(':delivery_date', $order->delivery_date());
		 $req->bindValue(':delivery_week', $order->delivery_week());
		 $req->bindValue(':delivery_instructions', $order->delivery_instructions());
		 $req->bindValue(':origin', $order->origin());
		 $req->bindValue(':status', $order->status());
		 $req->execute();
		 
		 foreach($req->errorInfo() as $e)
		{
			if($e != 0) echo $e;
		}
		$confirm = $this->getOrderByRef($order->ref());
			if($confirm != false){
				return $confirm;
			}
			else{
				return false;
			}
	}
	public function deleteOrder(Order $order){
		$this->pdo_db->exec("DELETE FROM ".$this->tb_prefix."privateorder WHERE ref = ".$order->ref());
		$this->pdo_db->exec("DELETE FROM ".$this->tb_prefix."privateorder_line WHERE fk_order = ".$order->id());
		$order= $this->getOrderByRef($order->ref());
		if(!$order) return true;
		else return false;
	}
	public function mapOrder($data){
		$order = new Order(array(
			"id"=>(int)$data['rowid'],
			"ref"=>$data['ref'],
			"total_amount"=>$data['total_amount'],
			"fk_customer"=>$data['fk_customer'],
			"customer_type"=>$data['customer_type'],
			"delivery_address"=>html_entity_decode($data['delivery_address']),
			"delivery_town"=>html_entity_decode($data['delivery_town']),
			"delivery_zip"	=>html_entity_decode($data['delivery_zip']),
			"delivery_instructions"=> html_entity_decode($data['delivery_instructions']),
			"delivery_date"	=>	$data['delivery_date'],
			"delivery_week"	=>	$data['delivery_week'],
			"delivery_cost"		=>	$data['delivery_cost'],
			"order_date"=>$data['order_date'],
			"origin"=>	$data['origin'],
			"status"=>$data['status']
	  ));
	  return $order;
  }
}