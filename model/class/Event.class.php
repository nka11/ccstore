<?php

class Event {
 /**
   *Event vs database
   * 
   */
	private	$id,
			$name,	// recipe name
			$price, // eventual cost of manifestation
			$fk_organizer, // call to an Organization ( or contact?)
			$description,
			$start_date,
			$end_date,
			$kind,

			//objects list
			$place, 	// {{ object Address }}
			$exposants, // exposants list
			$organizer, // object php class Organization
			$animations; // expected animations for this event  = {Vente directe, Buffet, etc...}
			
			//files
				// inscription

	public function __construct(array $data){
		$this->hydrate($data);
	}

	public function hydrate(array $data){
		foreach ($data as $key => $value){
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) $this->$method($value);
		}
	}

	//getter

	public function id(){return $this->id;}
	public function name(){return $this->name;}
	public function description() {return $this->description;}
	public function fk_organizer(){return $this->fk_organizer;}
	public function start_date() {return $this->start_date;}
	public function end_date() {return $this->end_date;}
	public function kind() {return $this->kind;}
	public function place() {return $this->place;}	
	public function price() {return $this->price;}
	public function exposants() {return $this->exposants;}
	public function organizer() {return $this->organizer;}
	public function animations()  {return $this->animations;}
	public function start_dateTime(){ return new DateTime($this->start_date);}
	public function end_dateTime(){ return new DateTime($this->end_date);}

	//setter

	public function setId($id) { $this->id = (int) $id;}
	public function setName($name) { $this->name = $name;}
	public function setDescription($description) { $this->description = $description;}
	public function setFk_organizer($fk_organizer) {$this->fk_organizer = $fk_organizer;}
	public function setFk_animation($fk_animation) {$this->fk_animation = $fk_animation;}
	public function setStart_date($start_date) { $this->start_date = $start_date;}
	public function setEnd_date($end_date) { $this->end_date = $end_date;}
	public function setKind($kind) { $this->kind = $kind;}
	public function setPrice($price) { $this->price = $price;}
	public function setPlace($place) { $this->place = $place;}
	public function setAnimations($animations) { $this->animations = (array) $animations;}
	public function setExposants($exposants) { $this->exposants = (array) $exposants;}
	public function setOrganizer($organizer) { $this->organizer = $organizer;}

}