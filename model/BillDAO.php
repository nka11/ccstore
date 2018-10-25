<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Bill.class.php");
include_once './vendor/autoload.php';

class BillDAO extends AbstractClient {
	public function getBills(){
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."bill".$string_type." ORDER BY order_date DESC");
		$req->execute();
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapBill($data);
			}
		}
		return $result;
	}
	/**
	 * @param Object Product a quantifier
	 */
	public function getBillById($billID){
		$result=array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."bill  
						WHERE rowid= ".$billID;
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		if($req->rowCount() == 1){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result= $this->mapBill($data);
			}
		}
		return $result;
	}
	public function createBill(Bill $bill){
		$lastEntrance= $this->getlastEntrance();
		$req_string= "INSERT INTO ".$this->tb_prefix."bill 
						SET supplier_code=:code,
							number=:number,
							amount=:amount,
							issue=:issue";
		$req = $this->pdo_db->prepare($req_string);
		$req->bindValue(":code", $bill->supplier_code());
		$req->bindValue(":number", $bill->number());
		$req->bindValue(":amount", $bill->amount());
		$req->bindValue(":issue", $bill->issue());
		$req->execute();
		$result= ($this->getLastEntrance()->id() > $lastEntrance->id())
				?	$this->getLastEntrance()
				:	array();
		return $result;
	}
	public function updateBill(Bill $bill){
		$req_string= "UPDATE ".$this->tb_prefix."bill 
						SET supplier_code=:code,
							amount=:amount,
							issue=:issue 
						WHERE rowid=".$bill->id();
		$req = $this->pdo_db->prepare($req_string);
		$req->bindValue(":code", $bill->supplier_code());
		$req->bindValue(":number", $bill->number());
		$req->bindValue(":amount", $bill->amount());
		$req->bindValue(":issue", $bill->issue());
		$req->execute();
		$result= ($this->getBill($bill->id())
				?	$this->getBill($bill->id())
				:	array();
		return $result;
	}
	public function getLastEntrance(){
		$req_string= "SELECT * FROM ".$this->tb_prefix."bill 
						ORDER BY rowid DESC 
						LIMIT 1";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		if($req->rowCount() == 1){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result= $this->mapBill($data);
			}
		}
		return $result;
	}
	public function mapBill($data){
		$bill= new Bill(array(
			"id"=>	$data["rowid"],
			"number"=> $data["number"]
			"supplier_code"=> $data["supplier_code"],
			"issue"=> $data["date_issue"],
			"amount"=> $data["amount"]
			));
		return $bill;
	
}