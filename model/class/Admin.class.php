<?php

	class Admin	{
		
		private $login,
				$pw;
				
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
		
		public function login() { return $this->login;}
		public function pw()	{ return $this->pw;}
		
		//setter
		
		public function setLogin($login)	{ $this->login = $login;}
		public function setPw($pw)			{ $this->pw = $pw;}
		
		
	}