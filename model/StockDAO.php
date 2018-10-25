<?php
require_once("./model/AbstractClient.php");
include_once './vendor/autoload.php';

class StockDAO extends AbstractClient {
	public function getQuantities($products){
		foreach($products as $p){
						// SEPARER ICI LES PRODUITS QUI NE NECESSITENT PAS D'ETRE STOCKES -------------------------------------------
			$q=0;
			$req_string= "SELECT * FROM ".$this->tb_prefix."stock 
							WHERE fk_product= ".$p->id();
			$req = $this->pdo_db->prepare($req_string);
			$req->execute();
			if($req->rowCount() > 0){
				while($data = $req->fetch(PDO::FETCH_ASSOC)){
					$q+= $data['quantity'];
				}
			}
			$p->setQuantity($q);
		}
		return $products;
	}
	/**
	 * @param Object Product a quantifier
	 */
	public function getQuantity($product){
		$q=0;
		$req_string= "SELECT * FROM ".$this->tb_prefix."stock 
						WHERE fk_product= ".$product->id();
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		if($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$q= $this->mapProduct($data);
			}
		}
		$product->setQuantity($q);
	}
}