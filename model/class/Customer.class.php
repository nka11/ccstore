<?php

	class Customer {
 /**
   *Customer vs database
   * 
   */
   
		private	$id,
					$label,
					$password,
					$lastname,	
					$name,
					$email,
					$address,
					$town,
					$zip,
					$phone,
					$rank,
					$email_code;
		
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
		public function password(){return $this->password;}
		public function lastname(){return $this->lastname;}
		public function name(){return $this->name;}
		public function email(){return $this->email;}
		public function address(){return $this->address;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function phone(){return $this->phone;}
		public function rank(){return $this->rank;}
		public function email_code(){ return $this->email_code;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setPassword($pw){ $this->password = $pw;}
		public function setLastname($lastname) { $this->lastname = $lastname;}
		public function setName($name) { $this->name = $name;}
		public function setEmail($email){$this->email = $email;}
		public function setAddress($address){$this->address = $address;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setPhone($phone){$this->phone = $phone;}
		public function setRank($rank){$this->rank = $rank;}
		public function setEmail_code($code) {$this->email_code= $code;}
		
	}
						