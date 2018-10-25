<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Supplier.class.php");
require_once("./model/class/SupplierContact.class.php");
include_once './vendor/autoload.php';

class SupplierDAO extends AbstractClient {
	public function getSuppliers(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapSupplier($data);
			}
			$result = $this->getSuppliersContacts($result);
		}
		return $result;
	}
		public function getSuppliersContacts($suppliers){
			foreach($suppliers as $supplier){
				$contacts=array();
				$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier_contact WHERE fk_supplier=".$supplier->id());
				$req->execute();
				while($data = $req->fetch(PDO::FETCH_ASSOC)){
					$contacts[] = $this->mapSupplierContact($data);
				}
				$supplier->setContacts($contacts);
			}
			return $suppliers;
		}
	 /**
   * @param id $id Id supplier à récupérer
   */
  public function getSupplierById($id) {
	$result=array();
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapSupplier($data);
		}
		$result = $this->getSupplierContacts($result);
	}
	return $result;
  }
  public function getSupplierByLabel($label){
	$result=array();
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier WHERE supplier_label=:label");
	$req->bindValue(':label', $label);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapSupplier($data);
		}
		$result = $this->getSupplierContacts($result);
	}
	return $result;
  }
	public function getSupplierContacts($supplier){
		$contacts= array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier_contact WHERE fk_supplier=".$supplier->id());
		$req->execute();
		while($data2 = $req->fetch(PDO::FETCH_ASSOC)){
			$contacts[] = $this->mapSupplierContact($data2);
		}
		$supplier->setContacts($contacts);
		return $supplier;
	}
  public function createSupplier(Supplier $supplier){
	  $existing = $this->getSupplierByLabel($supplier->label());
	  // a supplier exist with that label, returning null
		if ($existing != false) {
		  return false;
		}
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."supplier SET supplier_label=:label, supplier_code=:code, supplier_phone=:phone, supplier_email=:email, supplier_desc=:description, supplier_address=:address, supplier_zip=:zip, supplier_department=:department, supplier_town=:town");
		$req->bindValue(':label', $supplier->label());
		$req->bindValue(':code', $supplier->code());
		$req->bindValue(':phone', $supplier->phone());
		$req->bindValue(':email', $supplier->email());
		$req->bindValue(':description', $supplier->description());
		$req->bindValue(':address', $supplier->address());
		$req->bindValue(':zip', $supplier->zip());
		$req->bindValue(':town',$supplier->town());
		$req->bindValue(':department',$supplier->department());
		$req->execute();
		
		$confirm = $this->getSupplierByLabel($supplier->label());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
	public function updateSupplier(Supplier $supplier){
	  $req=$this->pdo_db->prepare("UPDATE ".$this->tb_prefix."supplier SET supplier_label=:label, supplier_code=:code, supplier_phone=:phone, supplier_email=:email, supplier_desc=:description, supplier_address=:address, supplier_zip=:zip, supplier_town=:town, supplier_department=:department WHERE rowid=:id");
	  $req->bindValue(':label', $supplier->label());
	  $req->bindValue(':code', $supplier->code());
	  $req->bindValue(':phone', $supplier->phone());
	  $req->bindValue(':email', $supplier->email());
	  $req->bindValue(':description', $supplier->description());
	  $req->bindValue(':address', $supplier->address());
	  $req->bindValue(':zip', $supplier->zip());
	  $req->bindValue(':town',$supplier->town());
	  $req->bindValue(':department',$supplier->department());
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
	public function createSupplierContact(SupplierContact $contact){
		$rowid= $this->getlastSupplierContact()->id();
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."supplier_contact
										SET fk_supplier=:fk_supplier,
											supplier_contact_gender=:gender,
											supplier_contact_name=:name,
											supplier_contact_lastname=:lastname,
											supplier_contact_email=:email,
											supplier_contact_phone=:phone,
											supplier_contact_address=:address,
											supplier_contact_zip=:zip,
											supplier_contact_town=:town"
									);
		// Bind values
		$req->bindValue(':fk_supplier', $contact->fk_supplier());
		$req->bindValue(':gender', $contact->gender());
		$req->bindValue(':name', $contact->name());
		$req->bindValue(':lastname', $contact->lastname());
		$req->bindValue(':email', $contact->email());
		$req->bindValue(':phone', $contact->phone());
		$req->bindValue(':address', $contact->address());
		$req->bindValue(':zip', $contact->zip());
		$req->bindValue(':town', $contact->town());
		$req->execute();
		
		//confirm
		$confirm= $this->getlastSupplierContact();
		if($confirm->id() > $rowid) return $confirm;
		else return false;
		
	}
	public function getlastSupplierContact(){
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."supplier_contact ORDER BY rowid DESC LIMIT 1");
		$req->execute();
		if ($req->rowCount() == 1)	$result= $this->mapSupplierContact($req->fetch(PDO::FETCH_ASSOC));
		return $result;
	}
	public function mapSupplier($data){
		$supplier = new Supplier(array(
			"id"=>$data['rowid'],
			"label"=> html_entity_decode($data['supplier_label']),
			"code"=>html_entity_decode($data['supplier_code']),
			"phone"=>html_entity_decode($data['supplier_phone']),
			"email"=>html_entity_decode($data['supplier_email']),
			"description"=>html_entity_decode($data['supplier_desc']),
			"address"=>html_entity_decode($data['supplier_address']),
			"town"=>html_entity_decode($data['supplier_town']),
			"zip"=>$data['supplier_zip'],
			"department"=>$data['supplier_department']
		));
		return $supplier;
	}
	public function mapSupplierContact($data){
		$contact= new SupplierContact( array(
				"id"		=>	$data["rowid"],
				"fk_supplier"=> $data['fk_supplier'],
				"name"		=>	html_entity_decode($data['supplier_contact_name']),
				"lastname"	=>	html_entity_decode($data['supplier_contact_lastname']),
				"gender"	=>	$data['supplier_contact_gender'],
				"email"		=>	html_entity_decode($data['supplier_contact_email']),
				"phone"		=>	$data['supplier_contact_phone'],
				"address"	=>	html_entity_decode($data['supplier_contact_address']),
				"zip"		=>	$data['supplier_contact_zip'],
				"town"		=>	$data['supplier_contact_town']
				));
		return $contact;
	}
}