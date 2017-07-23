<?php

	class Admin {
		private 	$id_a, 		//db user id
						$pseudo, 	//UNIQUE, used as login for admin user
						$password;  // Must be scrypted before saved in db.
				
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
		public function id_a() {return $this->id_a;}
		public function pseudo() {return $this->pseudo;}
		public function password() {return $this->password;}
		
		//setter
		public function setId_a($id) { $this->id_a = (int) $id;}
		public function setPseudo($pseudo) { $this->pseudo = $pseudo;}
		public function setPassword($password) { $this->password= $password;}
	}
