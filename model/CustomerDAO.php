<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Customer.class.php");
include_once './vendor/autoload.php';

class CustomerDAO extends AbstractClient {
	public function getCustomers(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM customer");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapCustomer($data);
			}
		}
		else {
			return false;
		}
		return $result;
	}
	 /**
   * @param id $id Id customer a récupérer
   */
  public function getCustomerById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM customer WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapCustomer($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  /**
   * @param email $email email customer a récupérer
   */
public function getCustomerByEmail($email) {
	$req = $this->pdo_db->prepare("SELECT * FROM customer WHERE customer_email=:email");
	$req->bindValue(':email', $email);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapCustomer($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function createCustomer(Customer $customer) {
		$existing = $this->getCustomerByEmail($customer->email());
		// a client exist with that email, returning null
		if ($existing != false) {
		  return false;
		}
		$req= $this->pdo_db->prepare('INSERT INTO customer SET customer_label=:label, customer_lastname=:lastname, customer_name=:name, customer_email=:email, 
																								customer_pw=:password, customer_address=:address, customer_town=:town, customer_zip=:zip, customer_phone=:phone,
																									customer_valid_email=:email_code');
		$req->bindvalue(':label', $customer->label());
		$req->bindValue(':lastname', $customer->lastname());
		$req->bindValue(':name', $customer->name());
		$req->bindValue(':email', $customer->email());
		$req->bindValue(':password', $customer->password());
		$req->bindValue(':address', $customer->address());
		$req->bindValue(':zip', $customer->zip());
		$req->bindValue(':town', $customer->town());
		$req->bindValue(':phone', $customer->phone());
		$req->bindValue(':email_code', $customer->email_code());
		$req->execute();
		
		$confirm = $this->getCustomerByEmail($customer->email());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
	}
	
	public function updateCustomer(Customer $customer){
		$existing= $this->getCustomerByEmail($customer->email());
		// a client exist with that email, returning null
		if($existing != true) return false;
		
		$req= $this->pdo_db->prepare('UPDATE customer SET customer_label=:label, customer_lastname=:lastname, customer_name=:name, customer_email=:email, 
																								customer_pw=:password, customer_address=:address, customer_town=:town, customer_zip=:zip, customer_phone=:phone, customer_rank=:rank,  
																								customer_valid_email=:email_code WHERE rowid =:id');
		$req->bindvalue(':label', $customer->label());
		$req->bindValue(':lastname', $customer->lastname());
		$req->bindValue(':name', $customer->name());
		$req->bindValue(':email', $customer->email());
		$req->bindValue(':password', $customer->password());
		$req->bindValue(':address', $customer->address());
		$req->bindValue(':zip', $customer->zip());
		$req->bindValue(':town', $customer->town());
		$req->bindValue(':phone', $customer->phone());
		$req->bindValue(':rank', $customer->rank());
		$req->bindValue(':email_code', $customer->email_code());
		$req->bindValue(':id', $customer->id());
		$req->execute();
		
		$confirm = $this->getCustomerByEmail($customer->email());
		if($confirm != false) return $confirm;
		else return false;
	}
	/*
	public function login(Customer $customer){
		$req=$this->pdo_db->prepare("SELECT * FROM customer WHERE customer_pw=:password");
		$req->bindValue(':password', $customer->password());
		$req->execute();
		
		if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapCustomer($data);
		}
			return $result;
		}else{
			return false;
		}
	}*/
	public function mapCustomer($data){
		$customer = new Customer(array(
				"id"=>$data['rowid'],
				"label"=>$data['customer_label'],
				"password"=>$data['customer_pw'],
				"lastname"=> html_entity_decode($data['customer_lastname']),
				"name"=>html_entity_decode($data['customer_name']),
				"email"=>$data['customer_email'],
				"address"=>html_entity_decode($data['customer_address']),
				"town"=>html_entity_decode($data['customer_town']),
				"zip"=>$data['customer_zip'],
				"phone"=>$data['customer_phone'],
				"rank"=>html_entity_decode($data['customer_rank']),
				"email_code"=> $data['customer_valid_email']
			));
			
			return $customer;
	}
}