<?php

	class User{
 /**
   * User vs database
   * 
   */
		private		$id,
					$lastname,
					$name,
					$email,
					$phone,
					$password,
					$balance,
					$registration_date,
					$email_code,
					
					//php support request
					$temp_code,
					
					//php list
					$orders,
					$user_address,
					$user_adhesions=array();
		
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
		
		public function id() {return $this->id;}
		public function lastname() {return $this->lastname;}
		public function name() {return $this->name;}
		public function email() {return $this->email;}
		public function phone() {return $this->phone;}
		public function password(){return $this->password;}
		public function user_address() {return $this->user_address;}
		public function balance() {return $this->balance;}
		public function rank(){return $this->rank;}
		public function registration_date() {return $this->registration_date;}
		public function email_code(){ return $this->email_code;}
		public function temp_code(){ return $this->temp_code;}
		public function user_adhesions() { return $this->user_adhesions;}
		public function orders() { return $this->orders;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLastname($lastname) { $this->lastname = $lastname;}
		public function setName($name) { $this->name = $name;}
		public function setEmail($email) { $this->email = $email;}
		public function setPhone($phone) { $this->phone = $phone;}
		public function setPassword($pw){ $this->password = $pw;}
		public function setUser_address($user_address) { $this->user_address = $user_address;}
		public function setBalance($balance) {$this->balance = $balance;}
		public function setRank($rank){$this->rank = $rank;}
		public function setRegistration_date($reg_date) {$this->registration_date= $reg_date;}
		public function setEmail_code($code) {$this->email_code= $code;}
		public function setTemp_code($code) {$this->temp_code= $code;}
		public function setUser_adhesions($adhesions) {$this->user_adhesions= $adhesions;}
		public function setOrders($orders) {$this->orders= $orders;}

		//function
		
		public function is_Member() {
			foreach($this->user_adhesions as $adh){
				if($adh['date_adhesion'] == date("Y")) return true;
				else return false;
			}
		}
		public function status_orders($status){
			$status_orders= array();
			if(is_array($this->orders)){
				foreach( $this->orders as $o){
					if($o->status() == $status) $status_orders[]= $o;
				}
			}
		}
	}