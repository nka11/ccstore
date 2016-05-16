<?php

	class Supplier {
 /**
   * Représente unproducteur vs model dolibarr :
   * 
   */
		private	$id,
						$name, 
						$name_alias,
						$address,
						$zip,
						$town,
						$phone,
						$email,
						$web,
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
		public function name() {return $this->name;}
		public function name_alias() {return $this->name_alias;}
		public function address() {return $this->address;}
		public function zip() {return $this->zip;}
		public function town() { return $this->town;}
		public function phone() { return $this->phone;}
		public function web() { return $this->web;}
		public function description() {return $this->description;}
				
			
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setName($name) { $this->name = $name;}
		public function setName_alias($name_alias) { $this->name_alias = $name_alias;}
		public function setAdresse($address) { $this->address = $address;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setTown($town) {$this->town = $town;}
		public function setPhone($phone) { $this->phone = $phone;}
		public function setWeb($web) { $this->web = $web;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		
	}