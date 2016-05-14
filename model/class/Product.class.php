<?php

class Product {
  /**
   * Représente un produit vs model dolibarr :
   * Un produit peut avoir plusieurs producteurs
   * Un produit peut avoir plusieures catégories
   *
   */
		private 	$id,
						$title,
						$ref, // reference produit
						$price,
						$tva,
						$description = "",
						$is_active,
						$img,
						
						//objets : 
						
						$categories,
						$producers;
		
		public function __construct(array $data)	{
			$this->hydrate($data);
		}
    /**
     * loads data from a db record
     */
		public function hydrate(array $data){
			foreach ($data as $key => $value){
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method)){
						$this->$method($value);
				}
			}
		}	
		//getter
		public function id() {return $this->id;}
		public function title() {return $this->title;}
		public function price() {return $this->price;}
		public function tva() {return $this->tva;}
		public function description() {return $this->description;}
		public function is_active() { return $this->is_active;}
		public function img() {return $this->img;}
		public function ref() {return $this->ref;}
		
		public function categories() { return $this->categories;}
		public function producers() { return $this->producers;}
		//setter
		public function setId($id) { $this->id = (int) $id;}
		public function setTitle($title) { $this->title = $title;}
		public function setPrice($price) { $this->price =  $price;}
		public function setCategories($categories) { $this->categories =  $categories;}
		public function setTva($tva) { $this->tva =  $tva;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		public function setIs_active($is_active) { $this->is_active = $is_active;}
		public function setImg($img) { $this->img = $img;}
		public function setRef($ref) { $this->ref = $ref;}
				
}
