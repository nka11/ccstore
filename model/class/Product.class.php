<?php

	class Product {
 /**
   *Product vs database
   * 
   */
   
		private	$id,
						$label,	// product name
						$price_unit,
						$price_deci,
						$price,
						$package_weight,
						$weight_unit,
						$packaging,
						$description,
						$status,
						$fk_cat,
						$fk_supplier,
						
						//object
						$category,
						$supplier;
		
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
		public function price(){return $this->price;}
		public function price_unit(){return $this->price_unit;}
		public function price_deci(){return $this->price_deci;}
		public function weight_unit(){return $this->weight_unit;}
		public function package_weight(){return $this->package_weight;}
		public function packaging(){return $this->packaging;}
		public function description(){return $this->description;}
		public function status(){return $this->status;}
		public function fk_cat(){return $this->fk_cat;}
		public function fk_supplier(){return $this->fk_supplier;}
		public function weight_unit_ref(){
			if($this->weight_unit=="g"){
				return "Kg";
			}elseif($this->weight_unit=='cl'){
				return "L";
			}
			else return $this->weight_unit;
		}
		public function priceper() {
			if($this->weight_unit == "g"){
				$this->priceper = ($this->price*1000)/$this->package_weight;
				return round($this->priceper, 2, PHP_ROUND_HALF_UP);
			}
			elseif($this->weight_unit=='cl'){
				$this->priceper = ($this->price*100)/$this->package_weight;
				return round($this->priceper, 2, PHP_ROUND_HALF_UP);
			}
			else return $this->price;
		}
		public function category(){return $this->category;}
		public function supplier(){return $this->supplier;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setLabel($label) { $this->label = $label;}
		public function setPrice($price) { 
			$this->price =  round($price, 1, PHP_ROUND_HALF_UP);
			$this->price_unit = floor($this->price);
			$this->price_deci = ($this->price-floor($this->price))*100;
		}
		public function setPackage_weight($package_weight) { $this->package_weight = $package_weight;}
		public function setWeight_unit($weight_unit) {$this->weight_unit = $weight_unit;}
		public function setPackaging($packaging){$this->packaging = $packaging;}
		public function setDescription($description){$this->description= $description;}
		public function setStatus($status) { $this->status = $status;}
		public function setFk_cat($fk_cat){$this->fk_cat= (int) $fk_cat;}
		public function setFk_supplier($fk_supplier) { $this->fk_supplier = (int) $fk_supplier;}
		public function setCategory($category){ $this->category= $category;}
		public function setSupplier($supplier){ $this->supplier= $supplier;}
		
	}
						