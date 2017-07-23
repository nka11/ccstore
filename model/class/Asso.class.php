<?php

	class Asso {
 /**
   *asso vs database
   * 
   */
   
		private	$id,
						$label,	// asso name
						$president,
						$tresorier,
						$secretaire,
						$adress,
						$town,
						$zip,
						$logo_extension;
												
		
		public function __construct(array $data)
		{
			$this->hydrate($data);
		}
		
		public function hydrate(array $data)
		{
			foreach ($data as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
						$this->$method($value);
				}
			}
		}
		
		//getter
		
		public function id(){return $this->id;}
		public function label(){return $this->label;}
		public function president(){return $this->president;}
		public function tresorier(){return $this->tresorier;}
		public function secretaire(){return $this->secretaire;}
		public function adress(){return $this->adress;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function logo_extension(){return $this->logo_extension;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setPresident($president) { $this->president= $president;}
		public function setTresorier($tresorier) { $this->tresorier = $tresorier;}
		public function setSecretaire($secretaire) {$this->secretaire= $secretaire;}
		public function setAdress($adress){$this->adress = $adress;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip){$this->zip= $zip;}
		public function setLogo_extension($logo_extension) { $this->logo_extension = $logo_extension;}
		
	}
						