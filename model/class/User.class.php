<?php

	class User {
 /**
   *Customer vs database
   * 
   */
		private	$id,
					$password,
					$login,
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
		public function password(){return $this->password;}
		public function login(){return $this->email;}
		public function rank(){return $this->rank;}
		public function email_code(){ return $this->email_code;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setPassword($pw){ $this->password = $pw;}
		public function setLogin($login){$this->login = $login;}
		public function setRank($rank){$this->rank = $rank;}
		public function setEmail_code($code) {$this->email_code= $code;}
		
	}