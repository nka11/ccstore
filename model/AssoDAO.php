<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Asso.class.php");
include_once './vendor/autoload.php';

class ProductDAO extends AbstractClient {
	public function getAsso(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM asso");
		$req->execute();
		
		if ($req->rowCount() ==1){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapAsso($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
  
   public function createAsso(Asso $asso){
	  $existing = $this->getAsso();
		// an asso allready exist, returning null
		if ($existing != false) {
		  return false;
		}
		$req = $this->pdo_db->prepare('INSERT INTO asso SET  label=:label, president=:president, tresorier=:tresorier, secretaire= :secretaire, adress=:adress, town=:town, zip=:zip');
		$req->bindValue(':label', $asso->label());
		$req->bindValue(':president', $asso->president());
		$req->bindValue(':tresorier', $asso->tresorier());
		$req->bindValue(':secretaire', $asso->secretaire());
		$req->bindValue(':adress', $asso->adress());
		$req->bindValue(':town', $asso->town());
		$req->bindValue(':zip', $asso->zip());
		$req->execute();
		
		$confirm = $this->getAsso();
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  
  public function editAsso(Asso $asso){
	$req= $this->pdo_db->prepare('UPDATE asso SET label=:label, president=:president, tresorier=:tresorier, secretaire= :secretaire, adress=:adress, town=:town, zip=:zip WHERE rowid=:id');
	$req->bindValue(':label', $asso->label());
	$req->bindValue(':president', $asso->president());
	$req->bindValue(':tresorier', $asso->tresorier());
	$req->bindValue(':secretaire', $asso->secretaire());
	$req->bindValue(':adress', $asso->adress());
	$req->bindValue(':town', $asso->town());
	$req->bindValue(':zip', $asso->zip());
	 $req->bindValue(':id', $asso->id(), PDO::PARAM_INT);
	 $req->execute();
		
		$confirm = $this->getAsso();
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  
  public function deleteAsso(Asso $asso){
	  $req= $this->pdo_db->exec('DELETE FROM asso WHERE rowid='.$asso->id());
  }
  
  public function mapProduct($data){
		$product = new Product(array(
			"id"=>(int)$data['rowid'],
			"label"=>$data['label'],
			"president"=>$data['president'],
			"tresorier"=>(int)$data['tresorier'],
			"secretaire"=>$data['secretaire'],
			"adress"=>$data['adress'],
			"town"=>$data['town'],
			"zip"=>$data['zip'],
			"logo_extension"=>$data['logo_extension']
	  ));
	  return $asso;
  }

}