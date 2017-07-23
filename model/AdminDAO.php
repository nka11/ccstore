<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Admin.class.php");
include_once './vendor/autoload.php';

class AdminDAO extends AbstractClient {
  public function getAdmins() {
	$result = array();
	$req = $this->pdo_db->prepare("SELECT * FROM admin");
	$req->execute();
	
	while($data = $req->fetch(PDO::FETCH_ASSOC)){
		$result[]= $this->mapAdmin($data);
    }
    return $result;
  }

  public function getAdminByPseudo($pseudo) {
    $result = array();
	$req = $this->pdo_db->prepare("SELECT * FROM admin WHERE pseudo='".$pseudo."'");
	$req->execute();
	while($data = $req->fetch(PDO::FETCH_ASSOC)){
		$result[]= $this->mapAdmin($data);
    }
	if(count($result)==1){
		return $result[0];
	}
	else{
		return false;
	}
  }

  public function getAdminById($id_a) {
    $result = array();
	$req = $this->pdo_db->prepare("SELECT * FROM admin WHERE rowid=:id_a");
	$req->bindValue(":id_a", $id_a, PDO::PARAM_INT);
	$req->execute();
	
	while($data = $req->fetch(PDO::FETCH_ASSOC)){
		$result[]= $this->mapAdmin($data);
    }
	if(count($result)==1){
		return $result;
	}
	else{
		return false;
	}
  }
  public function updateAdmin(Admin $admin) {
    if ($admin->id_a() == null 
        || $admin->id_a() <= 0) { // admin must have an id
      return false;
    }
    $req= $this->pdo_db->prepare("UPDATE admin SET pseudo=:pseudo, password=:password WHERE rowid=:id_a");
	$req->bindValue(":pseudo", $admin->pseudo());
	$req->bindValue(":password", $admin->password());
	$req->bindValue(":id_a", $admin->id_a(), PDO::PARAM_INT);
	$req->execute();
	
	$admin= $this->getAdminByPseudo($admin->pseudo());
	return $admin;
  }
  public function deleteAdmin(Admin $admin) {
    $req= $this->pdo_db->prepare("DELETE FROM admin WHERE rowid=:id_a");
	$req->bindValue(":id_a", $admin->id_a(), PDO::PARAM_INT);
	
	$admin= getAdminByPseudo($admin->pseudo());
	return $admin;
  }

  public function login(Admin $admin) {
    $result = array();
	$req = $this->pdo_db->prepare("SELECT * FROM admin WHERE pseudo=:pseudo AND password=:password");
	$req->bindValue(":pseudo", $admin->pseudo());
	$req->bindValue(":password", sha1($admin->password()));
	$req->execute();
	while($data = $req->fetch(PDO::FETCH_ASSOC)){
		$result[]= $this->mapAdmin($data);
    }
	if(count($result)==1){
		return $result[0];
	}
	else{
		return false;
	}
  }

  public function createAdmin(Admin $admin) {
    $existing = $this->getAdminByPseudo($admin->pseudo());
    // an admin exist with that email, returning null
    if ($existing != false) {
      return false;
    }
    $req=$this->pdo_db->prepare("INSERT INTO admin SET pseudo=:pseudo, password=:password");
    $req->bindValue(":pseudo", $admin->pseudo());
	$req->bindValue(":password", sha1($admin->password()));
	$req->execute();
	
	$admin= $this->getAdminByPseudo($admin->pseudo());
	
	return $admin;
  }

  public function mapAdmin($data) {
    $admin = new Admin(array(
      "id_a" => (int)$data['rowid'],
      "pseudo" => $data['pseudo'],
      "password" => $data['password']
    ));
    return $admin;
  }
}
