<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Event.class.php");
include_once './vendor/autoload.php';

class EventDAO extends AbstractClient {
	public function getEvents(){
		$result = array();
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."event");
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapEvent($data);
			}
			if($result){
				foreach($result as $event){
					$animations= $this->getAnimationsByEvent($event);
					$event->setAnimations($animations);
				}
			}
		}
		return $result;
	}
	 /**
   * @param id $id Id event à récupérer
   */
  public function getEventById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."event WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapEvent($data);
		}
		$animations= $this->getAnimationsByEvent($event);
		$event->setAnimations($animations);
		return $result;
	}else{
		return false;
	}
  }
  public function getEventByName($name){
	$result=array();
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."event WHERE event_name=:name");
	$req->bindValue(':name', $name);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapEvent($data);
		}
		$animations= $this->getAnimationsByEvent($result);
		$result->setAnimations($animations);
		return $result;
	}else{
		return false;
	}
  }
  public function createEvent(Event $event){
		$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."event 
										SET event_name=:name, 
										fk_organizer=:fk_organizer, 
										event_kind=:kind, 
										description=:description, 
										start_date=:start, 
										end_date=:end, 
										event_price=:price, 
										event_address=:address, 
										event_zip=:zip, 
										event_town=:town");
		$req->bindValue(':name', $event->name());
		$req->bindValue(':fk_organizer', $event->fk_organizer());
		$req->bindValue('kind', $event->kind());
		$req->bindValue(':description', $event->description());
		$req->bindValue(':price', $event->price());
		$req->bindValue(':start', $event->start_date());
		$req->bindValue(':end', $event->end_date());
		$req->bindValue(':address', $event->place()['address']);
		$req->bindValue(':zip', $event->place()['zip']);
		$req->bindValue(':town',$event->place()['town']);
		$req->execute();
		foreach($event->animations() as $animID){
			$req= $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."event_animation
											SET fk_event=:id,
												fk_animation=:animID");
			$req->bindValue(':id', $event->id(), PDO::PARAM_INT);
			$req->bindValue(':animId', $animID, PDO::PARAM_INT);
		}
		$confirm = $this->getEventByName($event->name());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
	public function updateEvent(Event $event){
		$req=$this->pdo_db->prepare("UPDATE ".$this->tb_prefix."event 
									 SET event_name=:name, 
										fk_organizer=:fk_organizer, 
										event_kind=:kind, 
										description=:description, 
										start_date=:start, 
										end_date=:end, 
										event_price=:price, 
										event_address=:address, 
										event_zip=:zip, 
										event_town=:town 
									 WHERE rowid=:id");
		$req->bindValue(':name', $event->name());
		$req->bindValue(':fk_organizer', $event->fk_organizer());
		$req->bindValue('kind', $event->kind());
		$req->bindValue(':description', $event->description());
		$req->bindValue(':price', $event->price());
		$req->bindValue(':start', $event->start_date());
		$req->bindValue(':end', $event->end_date());
		$req->bindValue(':address', $event->place()['address']);
		$req->bindValue(':zip', $event->place()['zip']);
		$req->bindValue(':town',$event->place()['town']);
		$req->bindValue(':id', $event->id(), PDO::PARAM_INT);
		$req->execute();
		
		$confirm = $this->getEventByName($event->name());
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
				$result[] = $data['rowid'];
			}
		}
		return $result;
	}
	public function mapEvent($data){
		$place= $this->mapEventPlace($data);
		$event = new Event(array(
			"id"=>$data['rowid'],
			"name"=> html_entity_decode($data['event_name']),
			"fk_organizer"=>html_entity_decode($data['fk_organizer']),
			"kind"=>html_entity_decode($data['event_kind']),
			"price"=>html_entity_decode($data['event_price']),
			"description"=>html_entity_decode($data['description']),
			"start_date"=>html_entity_decode($data['start_date']),
			"end_date"=>html_entity_decode($data['end_date']),
			"place"=> $place
			));
		return $event;
	}
	public function mapEventPlace($data){
		$place = array(
					"address"=>html_entity_decode($data['event_address']),
					"zip"=>$data['event_zip'],
					"town"=>html_entity_decode($data['event_town'])
				);
		return $place;
	}
}