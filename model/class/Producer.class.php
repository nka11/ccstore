<?php

	class Producer {
 /**
   * Représente unproducteur vs model dolibarr :
   * 
   */
		private	$id,
						$denom, // Dénomination
						$title, // SAS, particulier, etc
						$address,
						$zip,
						$town,
						$phone,
						$description;
				
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);

		}
		
		public function hydrate(array $donnees)
		{
			foreach ($donnees as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
						$this->$method($value);
				}
			}
		}
		
		//getter
		
		public function id() {return $this->id;}
		public function denom() {return $this->denom;}
		public function title() {return $this->title;}
		public function address() {return $this->address;}
		public function zip() {return $this->zip;}
		public function town() { return $this->town;}
		public function phone() { return $this->phone;}
		public function description() {return $this->description;}
				
			
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setDenom($denom) { $this->denom = $denom;}
		public function setTitle($title) { $this->title = $title;}
		public function setAdresse($address) { $this->address = $address;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setTown($town) {$this->town = $town;}
		public function setPhone($phone) { $this->phone = $phone;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		
	}