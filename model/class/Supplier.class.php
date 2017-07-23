<?php

	class Supplier {
 /**
   * Supplier vs database
   * 
   */
		private	$id,
						$label, 	// organization name
						$name,  // supplier name
						$town,
						$zip,
						
						// array php
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
		public function name(){return $this->name;}
		public function town(){return $this->town;}
		public function zip(){return $this->zip;}
		public function products(){return $this->products;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setName($name) { $this->name = $name;}
		public function setTown($town) { $this->town = $town;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setProducts($products) { $this->products = $products;}
		
	}
						