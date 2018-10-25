<?php

	class Recipe {
 /**
   *Product vs database
   * 
   */
   
		private	$id,
						$name,	// recipe name
						$person_amount,
						$category,
						
						//objects list
						$ingredients, // ingredients list
						$equipments,  // equipments list
						$protocol;    // step list
		
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
		public function person_amount() {return $this->person_amount;}
		public function category() {return $this->category;}
		public function ingredients() {return $this->ingredients;}
		public function equipments() {return $this->equipments;}
		public function protocol() {return $this->protocol;}		
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setPerson_amount($num) { $this->person_amount = (int) $num;}
		public function setCategory($cat) { $this->category = $cat;}
		public function setIngredients($ingredients) { $this->ingredients = (array) $ingredients;}
		public function setEquipments($equipments) { $this->equipments = (array) $equipments;}
		public function setProtocol($protocol) { $this->protocol = (array) $protocol;}
		
	}