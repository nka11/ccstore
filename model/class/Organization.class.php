<?php

	class Organization {
 /**
   *Organization vs database
   * 
   */
   
		private	$id,
					$label,
					$kind,	// asso, company
					$siren,
					$email,
					$address,
					$town,
					$zip,
					$phone,
					$email_code,
					
					$contacts; // list php Object Contact
		
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
		public function label() {return $this->label;}
		public function kind() {return $this->kind;}
		public function siren() {return $this->siren;}
		public function email(){return $this->email;}
		public function address(){return $this->address;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function phone(){return $this->phone;}
		public function email_code(){ return $this->email_code;}
		public function contacts() { return $this->contacts;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setKind($kind) { $this->kind = $kind;}
		public function setSiren($siren) {$this->siren = $siren;}
		public function setEmail($email){$this->email = $email;}
		public function setAddress($address){$this->address = $address;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setPhone($phone){$this->phone = $phone;}
		public function setEmail_code($code) {$this->email_code= $code;}
		public function setContacts($contacts) {$this->contacts= $contacts;}
		
	}
						