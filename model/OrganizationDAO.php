<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Organization.class.php");
require_once("./model/class/OrganizationContact.class.php");
include_once './vendor/autoload.php';

class OrganizationDAO extends AbstractClient {
		/*
		 * Get all organizations
		 */
	public function getOrganizations(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapOrganization($data);
			}
			$result = $this->getOrganizationsContacts($result);
		}
		return $result;
	}
		public function getOrganizationsContacts($organizations){
				foreach($organizations as $orga){
						$contacts=array();
						$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization_contact WHERE fk_organization=".$orga->id());
						$req->execute();
						while($data = $req->fetch(PDO::FETCH_ASSOC)){
							$contacts[] = $this->mapOrganizationContact($data);
						}
						$orga->setContacts($contacts);
				}
				return $organizations;
			}
	/**
	 * @param id $id Id organization. Return object Organization class
     */
	public function getOrganizationById($id) {
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization WHERE rowid=:id");
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		if ($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result = $this->mapOrganization($data);
			}
			$result = $this->getOrganizationContacts($result);
		}
		return $result;
	}
	/**
     * @param email $email email organization a récupérer
    */
	public function getOrganizationByLabel($label) {
		$result=array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization WHERE struct_label=:label");
		$req->bindValue(':label', $label);
		$req->execute();
		if ($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result = $this->mapOrganization($data);
			}
			$result = $this->getOrganizationContacts($result);
		}
		return $result;
	}
		public function getOrganizationContacts($organization){
			$contacts= array();
			$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization_contact WHERE fk_organization=".$organization->id());
			$req->execute();
			while($data2 = $req->fetch(PDO::FETCH_ASSOC)){
				$contacts[] = $this->mapOrganizationContact($data2);
			}
			$organization->setContacts($contacts);
			return $organization;
		}
	public function createOrganization(Organization $organization) {
		$existing = $this->getOrganizationByLabel($organization->label());
		// a organization exist with that label, returning null
		if ($existing != false) {
		  return $existing;
		}
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."organization 
										SET struct_label=:label, 
											struct_kind=:kind, 
											struct_siren=:siren, 
											struct_address=:address,
											struct_email=:email, 
											struct_town=:town, 
											struct_zip=:zip, 
											struct_phone=:phone");
		$req->bindValue(':label', $organization->label());
		$req->bindValue(':kind', $organization->kind());
		$req->bindValue(':siren', $organization->siren());
		$req->bindValue(':email', $organization->email());
		$req->bindValue(':address', $organization->address());
		$req->bindValue(':zip', $organization->zip());
		$req->bindValue(':town', $organization->town());
		$req->bindValue(':phone', $organization->phone());
		$req->execute();
		
		$confirm = $this->getlastOrganization();
		if($confirm->label() == $organization->label()){
			return $confirm;
		}
		else{
			return false;
		}
	}
	
	public function updateOrganization(Organization $organization){
		$existing= $this->getOrganizationByLabel($organization->label());
		// a organization exist with that label, returning null
		if($existing != true) return false;
		$lastEnter= $this->getlastOrganization();
		$req= $this->pdo_db->prepare("UPDATE ".$this->tb_prefix."organization 
										SET	struct_label=:label, 
											struct_kind=:kind, 
											struct_siren=:siren, 
											struct_address=:address, 
											struct_email=:email, 
											struct_town=:town,
											struct_zip=:zip,
											struct_phone=:phone
										WHERE rowid =:id");
		$req->bindValue(':label', $organization->label());
		$req->bindValue(':kind', $organization->kind());
		$req->bindValue(':siren', $organization->siren());
		$req->bindValue(':email', $organization->email());
		$req->bindValue(':address', $organization->address());
		$req->bindValue(':zip', $organization->zip());
		$req->bindValue(':town', $organization->town());
		$req->bindValue(':phone', $organization->phone());
		$req->bindValue(':id', $organization->id());
		$req->execute();
		
		$confirm = $this->getlastOrganization();
		if($confirm->id() == $lastEnter->id()+1) return $confirm;
		else return false;
	}
	public function createOrganizationContact(OrganizationContact $contact){
		$rowid= $this->getlastOrganizationContact()->id();
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."organization_contact
										SET fk_organization=:rowid,
											organization_contact_gender=:gender,
											organization_contact_name=:name,
											organization_contact_lastname=:lastname,
											organization_contact_email=:email,
											organization_contact_phone=:phone,
											organization_contact_fn=:fn"
									);
		// Bind values
		$req->bindValue(':rowid', $contact->fk_organization());
		$req->bindValue(':gender', $contact->gender());
		$req->bindValue(':name', $contact->name());
		$req->bindValue(':lastname', $contact->lastname());
		$req->bindValue(':email', $contact->email());
		$req->bindValue(':phone', $contact->phone());
		$req->bindValue(':fn', $contact->fn());
		$req->execute();
		
		//confirm
		$confirm= $this->getlastOrganizationContact();
		if($confirm->id() > $rowid) return $confirm;
		else return false;
	}
	public function getlastOrganization(){
		$result= new Organization( array("id"=>0));
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization ORDER BY rowid DESC LIMIT 1");
		$req->execute();
		if ($req->rowCount() == 1){
			$result= $this->mapOrganization($req->fetch(PDO::FETCH_ASSOC));
			$result= $this->getOrganizationContacts($result);
		}
		return $result;
	}
	public function getlastOrganizationContact(){
		$result= new OrganizationContact( array("id"=>0));
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."organization_contact ORDER BY rowid DESC LIMIT 1");
		$req->execute();
		if ($req->rowCount() == 1)	$result= $this->mapOrganizationContact($req->fetch(PDO::FETCH_ASSOC));
		return $result;
	}
	public function mapOrganization($data){
		$organization = new Organization(array(
				"id"		=>	$data['rowid'],
				"label"		=> 	html_entity_decode($data['struct_label']),
				"kind"		=>	html_entity_decode($data['struct_kind']),
				"siren"		=>	$data['struct_siren'],
				"email"		=>	$data['struct_email'],
				"address"	=>	html_entity_decode($data['struct_address']),
				"town"		=>	html_entity_decode($data['struct_town']),
				"zip"		=>	$data['struct_zip'],
				"phone"		=>	$data['struct_phone']
			));
			return $organization;
	}
	public function mapOrganizationContact($data){
		$contact= new OrganizationContact( array(
				"id"		=>	$data["rowid"],
				"fk_organization"=> $data['fk_organization'],
				"name"		=>	html_entity_decode($data['organization_contact_name']),
				"lastname"	=>	html_entity_decode($data['organization_contact_lastname']),
				"gender"	=>	$data['organization_contact_gender'],
				"email"		=>	html_entity_decode($data['organization_contact_email']),
				"phone"		=>	$data['organization_contact_phone'],
				"fn"		=>	html_entity_decode($data['organization_contact_fn'])
				));
		return $contact;
	}
}