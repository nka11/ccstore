<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Supplier.class.php");
include_once './vendor/autoload.php';

class SupplierDAO extends AbstractClient {
	public function getSuppliers(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM supplier");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapSupplier($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	 /**
   * @param id $id Id supplier à récupérer
   */
  public function getSupplierById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM supplier WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapSupplier($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function getSupplierByLabel($label){
	$req = $this->pdo_db->prepare("SELECT * FROM supplier WHERE supplier_label=:label");
	$req->bindValue(':label', $label);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapSupplier($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function createSupplier(Supplier $supplier){
	  $existing = $this->getSupplierByLabel($supplier->label());
	  // a supplier exist with that label, returning null
		if ($existing != false) {
		  return false;
		}
		$req= $this->pdo_db->prepare('INSERT INTO supplier SET supplier_label=:label, supplier_name=:name, supplier_zip=:zip, supplier_town=:town');
		$req->bindValue(':label', $supplier->label());
		$req->bindValue(':name', $supplier->name());
		$req->bindValue('zip', $supplier->zip());
		$req->bindValue('town',$supplier->town());
		$req->execute();
		
		$confirm = $this->getSupplierByLabel($supplier->label());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  public function editSupplier(Supplier $supplier){
	  $req=$this->pdo_db->prepare('UPDATE supplier SET supplier_label=:label, supplier_name=:name, supplier_zip=:zip, supplier_town=:town WHERE rowid=:id');
	  $req->bindValue(':label', $supplier->label());
	  $req->bindValue(':name', $supplier->name());
	  $req->bindValue('zip', $supplier->zip());
	  $req->bindValue('town',$supplier->town());
	  $req->bindValue(':id', $supplier->id(), PDO::PARAM_INT);
	  $req->execute();
		
		$confirm = $this->getSupplierByLabel($supplier->label());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
	public function mapSupplier($data){
		$supplier = new Supplier(array(
			"id"=>$data['rowid'],
			"label"=> html_entity_decode($data['supplier_label']),
			"name"=>html_entity_decode($data['supplier_name']),
			"town"=>html_entity_decode($data['supplier_town']),
			"zip"=>$data['supplier_zip']
		));
		return $supplier;
	}
}