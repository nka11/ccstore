<?php

	class Customer{
 /**
   * Customer : Order attribut. PHP object ONLY!
   * 
   */
		private	$fk, // foreigh key
				$type; // Can be = "USER" , "CONTACT", "ORGANIZATION"
		
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
		
		public function fk(){return $this->fk;}
		public function type() {return $this->type;}
		
		//setter
		
		public function setFk($fk) { $this->fk = $fk;}
		public function setType($type) { $this->type= $type;}
	}
						