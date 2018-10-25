<?php
require_once("./model/class/Customer.class.php");
include_once './vendor/autoload.php';

class CustomerDAO extends ContactDAO{
	public function getCustomers(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$tb_prefix."contact AS c 
												WHERE EXISTS (
													SELECT * FROM cc_order as o
													WHERE c.rowid=o.fk_customer
												)");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapContact($data);
			}
		}
		else {
			return false;
		}
		return $result;
	}
}