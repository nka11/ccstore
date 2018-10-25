<?php

	class Animation {
 /**
   *Event vs database
   * 
   */
   
		private	$id,
				$name,	// recipe name
				$default_sell_price,
				$description,
				$number_of_animators;
					
				// php list
					//$materials;
		
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
		public function name(){return $this->name;}
		public function description() {return $this->description;}
		public function default_sell_price() {return $this->default_sell_price;}
		public function number_of_animators() {return $this->number_of_animators;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setName($name) { $this->name = $name;}
		public function setDescription($description) { $this->description = $description;}
		public function setDefault_sell_price($default_sell_price) { $this->default_sell_price = $default_sell_price;}
		public function setNumber_of_animators($nbr) { $this->number_of_animators = (int) $nbr;}
		
	}