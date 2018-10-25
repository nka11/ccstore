<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Animation.class.php");
include_once './vendor/autoload.php';

class AnimationDAO extends AbstractClient {
	public function getAnimations(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."animation");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapAnimation($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	 /**
   * @param id $id Id event à récupérer
   */
  public function getAnimationById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."animation WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapAnimation($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function getAnimationByName($name){
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."animation WHERE animation_name=:name");
	$req->bindValue(':name', $name);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapAnimation($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function createAnimation(Animation $animation){
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."animation SET animation_name=:name, default_sell_price=:default_sell_price, description=:description, number_of_animators=:number");
		$req->bindValue(':name', $animation->name());
		$req->bindValue(':default_sell_price', $animation->default_sell_price());
		$req->bindValue(':description', $animation->description());
		$req->bindValue(':number', $animation->number_of_animators());
		$req->execute();
		
		$confirm = $this->getAnimationByName($animation->name());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  public function updateAnimation(Animation $animation){
	  $req=$this->pdo_db->prepare("UPDATE ".$this->tb_prefix."animation SET animation_name=:name, default_sell_price=:default_sell_price, description=:description, number_of_animators=:number WHERE rowid=:id");
		$req->bindValue(':name', $animation->name());
		$req->bindValue(':default_sell_price', $animation->default_sell_price());
		$req->bindValue(':description', $animation->description());
		$req->bindValue(':number', $animation->number_of_animators());
	  $req->bindValue(':id', $animation->id(), PDO::PARAM_INT);
	  $req->execute();
		
		$confirm = $this->getAnimationByName($animation->name());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
	public function getAnimationsByEvent($event){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."event_animation WHERE fk_event=".$event->id());
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapAnimation($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	public function mapAnimation($data){
		$animation = new Animation(array(
			"id"=>$data['rowid'],
			"name"=> html_entity_decode($data['animation_name']),
			"default_sell_price"=>html_entity_decode($data['default_sell_price']),
			"description"=>html_entity_decode($data['description']),
			"number_of_animators"=>html_entity_decode($data['number_of_animators']),
		));
		return $animation;
	}
}