<?php

	class Category {
		
		private 	$id,
						$id_parent, // peut-être nul si pas de categorie parente
						$label,
						$description,
						
						$childCats, // list of undercategories (for parent categories) 
						$products, // list of products
						$badge; // Number of products in this category
				
		public function __construct(array $data){
			$this->hydrate($data);	
		}
		public function hydrate(array $data){
			foreach ($data as $key => $value){
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method)){
						$this->$method($value);
				}
			}
		}
		//setter
		public function id() { return $this->id;}
		public function id_parent() { return $this->id_parent;}
		public function label() { return $this->label;}
		public function description(){return $this->description;}
		public function badge(){return $this->badge;}
		public function childCats(){return $this->childCats;}
		public function products(){return $this->products;}
		//getter
		public function setId($id) { $this->id = (int) $id;}
		public function setId_parent($id_parent) { $this->id_parent = $id_parent;}
		public function setLabel($label) { $this->label = $label;}
		public function setDescription($description) {$this->description = $description;}
		public function setChildCats($childCats){$this->childCats= $childCats;}
		public function setBadge($badge) {$this->badge = $badge;}
		public function setProducts($products) {$this->products= $products;}
		//ctrl
		
		public function ctrlCategory($tag=NULL){}
		
		
	}