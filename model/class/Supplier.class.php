<?php

	class Supplier {
 /**
   * Supplier vs database
   * 
   */
		private	$id,
						$label, 	// organization name
						$code,  // supplier code
						$description,
						$phone,
						$email,
						$address,
						$town,
						$zip,
						$department,
						
						// array php
						$contacts,
						$products;
		
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
		public function code(){return $this->code;}
		public function phone(){return $this->phone;}
		public function email(){return $this->email;}
		public function description() {return $this->description;}
		public function address() {return $this->address;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function department(){return $this->department;}
		public function products(){return $this->products;}
		public function contacts(){return $this->contacts;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setCode($code) { $this->code = $code;}
		public function setPhone($phone) { $this->phone = $phone;}
		public function setEmail($email) { $this->email = $email;}
		public function setDescription($desc) { $this->description = $desc;}
		public function setAddress($address) { $this->address = $address;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setDepartment($department){$this->department = $department;}
		public function setProducts($products) { $this->products = $products;}
		public function setContacts($contacts) { $this->contacts = $contacts;}
		
	}
						