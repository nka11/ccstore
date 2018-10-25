<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Contact.class.php");
include_once './vendor/autoload.php';

class ContactDAO extends AbstractClient {
		/*
		 * Get all contacts
		 */
	public function getContacts(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."contact");
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
	/**
	 * @param id $id Id contact. Return object Contact class
     */
	public function getContactById($id) {
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."contact WHERE rowid=:id");
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		if ($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result = $this->mapContact($data);
			}
				return $result;
		}else{
			return false;
		}
	}
	/**
     * @param email $email email contact a récupérer
    */
	public function getContactByEmail($email) {
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."contact WHERE contact_email=:email");
		$req->bindValue(':email', $email);
		$req->execute();
		if ($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result = $this->mapContact($data);
			}
				return $result;
		}else{
			return false;
		}
	}
	public function getContactsByOrganization($organization){
	}
	public function createContact(Contact $contact) {
		$existing = $this->getContactByEmail($contact->email());
		// a contact exist with that email, returning null
		if ($existing != false) {
		  return $existing;
		}
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."contact SET contact_lastname=:lastname, contact_name=:name, contact_email=:email, 
																contact_address=:address, contact_town=:town,
																	contact_zip=:zip, contact_phone=:phone");
		$req->bindValue(':lastname', $contact->lastname());
		$req->bindValue(':name', $contact->name());
		$req->bindValue(':email', $contact->email());
		$req->bindValue(':address', $contact->address());
		$req->bindValue(':zip', $contact->zip());
		$req->bindValue(':town', $contact->town());
		$req->bindValue(':phone', $contact->phone());
		$req->execute();
		
		$confirm = $this->getContactByEmail($contact->email());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
	}
	
	public function updateContact(Contact $contact){
		$existing= $this->getContactByEmail($contact->email());
		// a contact exist with that email, returning null
		if($existing != true) return false;
		
		$req= $this->pdo_db->prepare("UPDATE ".$this->tb_prefix."contact SET contact_lastname=:lastname, contact_name=:name, contact_email=:email, 
															contact_address=:address, contact_town=:town, contact_zip=:zip, contact_phone=:phone 
																WHERE rowid =:id");
		$req->bindValue(':lastname', $contact->lastname());
		$req->bindValue(':name', $contact->name());
		$req->bindValue(':email', $contact->email());
		$req->bindValue(':address', $contact->address());
		$req->bindValue(':zip', $contact->zip());
		$req->bindValue(':town', $contact->town());
		$req->bindValue(':phone', $contact->phone());
		$req->bindValue(':id', $contact->id());
		$req->execute();
		
		$confirm = $this->getContactByEmail($contact->email());
		if($confirm != false) return $confirm;
		else return false;
	}
	protected function mapContact($data){
		$contact = new Contact(array(
				"id"=>$data['rowid'],
				"lastname"=> html_entity_decode($data['contact_lastname']),
				"name"=>html_entity_decode($data['contact_name']),
				"email"=>$data['contact_email'],
				"address"=>html_entity_decode($data['contact_address']),
				"town"=>html_entity_decode($data['contact_town']),
				"zip"=>$data['contact_zip'],
				"phone"=>$data['contact_phone']
			));
			return $contact;
	}
}