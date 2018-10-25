<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Member.class.php");
include_once './vendor/autoload.php';

class MemberDAO extends AbstractClient{
	public function getMembers(){
		$result = array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."member";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapMember($data);
			}
		}
		return $result;
	}
	public function getMemberById($memberID){
		$result= null;
		$req_string= "SELECT * FROM ".$this->tb_prefix."member WHERE rowid=".$memberID;
		$req= $this->pdo_db->prepare($req_string);
		$req->execute();
		
		while($data= $req->fetch(PDO::FETCH_ASSOC)){
				$result= $this->mapMember($data);
		}
		return $result;
	}
	public function getMembersByEventId($eventID){
		$result= array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."event_member_registration WHERE fk_event=".$eventID;
		$req= $this->pdo_db->prepare($req_string);
		$req->execute();
		while($data= $req->fetch(PDO::FETCH_ASSOC)){
				$result= $this->getMemberById($data['fk_member']);
		}
		return $result;
	}
	public function createMember($member){
		$lastEnter= $this->getLastEnter();
		$req = $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."member 
										SET member_name=:name, 
											member_lastname=:lastname, 
											member_fn=:fn,
											member_phone=:phone,
											member_email=:email,
											member_address=:address,
											member_zip=:zip,
											member_town=:town");
		$req->bindValue(':name', $member->name());
		$req->bindValue(':lastname', $member->lastname());
		$req->bindValue(':fn', $member->fn());
		$req->bindValue(':phone', $member->phone());
		$req->bindValue(':email', $member->email());
		$req->bindValue(':address', $member->address());
		$req->bindValue(':zip', $member->zip());
		$req->bindValue(':town', $member->town());
		$req->execute();
		
		$newMember= $this->getLastEnter();
		$newMember->setId(9);
		if($newMember->id() > $lastEnter->id()) return $newMember;
		else return false;
	}
	public function updateMember($member){
		$req = $this->pdo_db->prepare("UPDATE ".$this->tb_prefix."member 
										SET member_name=:name, 
											member_lastname=:lastname, 
											member_fn=:fn,
											member_phone=:phone,
											member_email=:email,
											member_address=:address,
											member_zip=:zip,
											member_town=:town 
										WHERE rowid=:id");
		$req->bindValue(':name', $member->name());
		$req->bindValue(':lastname', $member->lastname());
		$req->bindValue(':fn', $member->fn());
		$req->bindValue(':phone', $member->phone());
		$req->bindValue(':email', $member->email());
		$req->bindValue(':address', $member->address());
		$req->bindValue(':zip', $member->zip());
		$req->bindValue(':town', $member->town());
		$req->bindValue(':id', $member->id(), PDO::PARAM_INT);
		$req->execute();
		
		$newMember= $this->getMemberById($member->id());
		if($newMember) return $newMember;
		else return false;
	}
	public function getLastEnter(){
		$reqString="SELECT * FROM ".$this->tb_prefix."member 
						ORDER BY 'rowid' DESC LIMIT 1";
		$req= $this->pdo_db->prepare($reqString);
		$req->execute();
		$data=$req->fetch(PDO::FETCH_ASSOC);
		if($data) $lastEnter= $this->mapMember($data);
		else $lastEnter= new Member(array("id"=>0));
		return $lastEnter;
	}
	public function mapMember($data){
		$member= new Member( array(
				"id"=>$data['rowid'],
				"name"=>$data['member_name'],
				"lastname"=>$data['member_lastname'],
				"fn"=>$data['member_fn'],
				"phone"=>$data['member_phone'],
				"email"=>$data['member_email'],
				"address"=>$data['member_address'],
				"zip"=>$data['member_zip'],
				"town"=>$data['member_town']));
		return $member;
	}
}