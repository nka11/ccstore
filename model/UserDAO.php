<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/User.class.php");
include_once './vendor/autoload.php';

class UserDAO extends AbstractClient {
	public function getUsers(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapUser($data);
			}
			$result = $this->getUsersAddress($result);
			$result = $this->getUsersAdhesions($result);
		}
		else {
			return false;
		}
		return $result;
	}
	public function getUsersAddress($users){
		foreach($users as $user){
			$address=array();
			$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user_address WHERE fk_user=".$user->id());
			$req->execute();
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$address[] = $this->mapUserAddress($data);
			}
			$user->setUser_address($address);
		}
		return $users;
	}
	public function getUsersAdhesions($users){
		foreach($users as $user){
				$adhesions=array();
				$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user_adhesion WHERE fk_user=".$user->id());
				$req->execute();
				while($data = $req->fetch(PDO::FETCH_ASSOC)){
					$adhesions[] = $this->mapUserAdhesion($data);
				}
				$user->setUser_adhesions($adhesions);
			}
		return $users;
	}
	public function getUserAdhesions($user){
		$adhesions= array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user_adhesion WHERE fk_user=".$user->id());
		$req->execute();
		while($data3 = $req->fetch(PDO::FETCH_ASSOC)){
			$adhesions[] = $this->mapUserAdhesion($data3);
		}
		$user->setUser_adhesions($adhesions);
		return $user;
	}
	public function getUserAddress($user){
		$address= array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user_address WHERE fk_user=".$user->id());
		$req->execute();
		while($data = $req->fetch(PDO::FETCH_ASSOC)){
			$address[] = $this->mapUserAddress($data);
		}
		$user->setUser_address($address);
		return $user;
	}
	/**
     * @param id $id Id user a récupérer
    */
  public function getUserById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$user = $this->mapUser($data);
		}
		$user = $this->getUserAddress($user);
		$user = $this->getUserAdhesions($user);
		return $user;
	}else{
		return false;
	}
  }
  /**
   * @param email $email user_email
   */
public function getUserByEmail($email) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."user WHERE user_email=:email ");
	$req->bindValue(':email', $email);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$user = $this->mapUser($data);
		}
		$user = $this->getUserAddress($user);
		$user = $this->getUserAdhesions($user);
		return $user;
	}else{
		return false;
	}
  }
  public function createUser(User $user) {
		$existing = $this->getUserByEmail($user->email());
		// a user already exist with that email, returning null/false
		if ($existing != false) {
		  return false;
		}
		// prepare RECORDING NEW USER ACCOUNT
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."user
										SET	rowid=:id,
											user_password=:pw,
											user_account_time=NOW(),
											user_lastname=:lastname,
											user_name=:name,
											user_email=:email,
											user_phone=:phone,
											user_confirmed=:email_code
									");
		// Bind values
		$req->bindValue(':id', $user->id());
		$req->bindValue(':pw', $user->password());
		$req->bindValue(':email', $user->email());
		$req->bindValue(':lastname', $user->lastname());
		$req->bindValue(':name', $user->name());
		$req->bindValue(':phone', $user->phone());
		$req->bindValue(':email_code', $user->email_code());
		$req->execute();
		
		$confirm = $this->getUserByEmail($user->email());
		if($confirm != false){
			$user->setId($confirm->id());
			$this->createUserAddress($user);
			return $confirm;
		}
		else{
			return false;
		}
	}
	public function createUserAddress($user){
		foreach($user->user_address() as $address){
			$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."user_address
											SET fk_user=:rowid,
												label=:label,
												address=:address,
												zip=:zip,
												town=:town"
										);
			// Bind values
			$req->bindValue(':rowid', $user->id());
			$req->bindValue(':label', $address['label']);
			$req->bindValue(':address', $address['address']);
			$req->bindValue(':zip', $address['zip']);
			$req->bindValue(':town', $address['town']);
			$req->execute();
		}
	}
	public function createUserAdhesion($user){
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."user_adhesion
										SET fk_user=:rowid,
											date_adhesion=NOW()"
									);
		// Bind values
		$req->bindValue(':rowid', $user->id());
		$req->execute();
	}
	public function updateUser(User $user){
		$existing= $this->getUserByEmail($user->email());
		// an user exist with that email, returning null
		if(!$existing) return false;
		echo "utilisateur reconnu / ".$user->email();
		$req= $this->pdo_db->prepare("UPDATE ".$this->tb_prefix."user 
										SET user_password=:pw,
											user_lastname=:lastname,
											user_name=:name,
											user_email=:email,
											user_phone=:phone,
											user_confirmed=:code
										WHERE rowid =:id
									");
		$req->bindValue(':pw', $user->password());
		$req->bindValue(':email', $user->email());
		$req->bindValue(':lastname', $user->lastname());
		$req->bindValue(':name', $user->name());
		$req->bindValue(':phone', $user->phone());
		$req->bindValue(':code', $user->email_code());
		$req->bindValue(':id', $user->id());
		$req->execute();
		
		$confirm = $this->getUserByEmail($user->email());
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
	public function mapUser($data){
		$user = new User(array(
				"id"=>$data['rowid'],
				"password"=>$data['user_password'],
				"lastname"=> html_entity_decode($data['user_lastname']),
				"name"=>html_entity_decode($data['user_name']),
				"email"=>$data['user_email'],
				"phone"=>$data['user_phone'],
				"registration_date"=>$data['user_account_time'],
				"email_code"=> $data['user_confirmed']
			));
			
			return $user;
	}
	public function mapUserAddress($data2){
		$address = array(
					"label"=>html_entity_decode($data2['label']),
					"address"=>html_entity_decode($data2['address']),
					"zip"=>$data2['zip'],
					"town"=>html_entity_decode($data2['town'])
				);
		return $address;
	}
	public function mapUserAdhesion($data3){
		$date_adhesion = new DateTime($data3['date_adhesion']);
		$year = $date_adhesion->format("Y");
		$adhesion = array(
					"date_adhesion"=>$year
				);
		return $adhesion;
	}
}