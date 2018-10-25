<?php

	class Member{
 /**
   * Member vs database
   * 
   */
		private	$id,
				$name,
				$lastname,
				$address,
				$zip,
				$town,
				$phone,
				$email,
				$fn;
		
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
		public function name() {return $this->name;}
		public function lastname() {return $this->lastname;}
		public function email(){return $this->email;}
		public function address(){return $this->address;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function phone(){return $this->phone;}
		public function fn() {return $this->fn;}
		
		//setter
		
		public function setId($id) { $this->id = $id;}
		public function setName($name) { $this->name= $name;}
		public function setLastname($lastname) { $this->lastname= $lastname;}
		public function setEmail($email){$this->email = $email;}
		public function setAddress($address){$this->address = $address;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setPhone($phone){$this->phone = $phone;}
		public function setFn($fn) { $this->fn= $fn;}
	}