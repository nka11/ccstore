<?php

	class OrderLine {
 /**
   *Orderline vs database
   * 
   */
   
		private	$id,
						$fk_product,
						$fk_order,
						$amount,
						$value,
						
						// objet php
						$product;
		
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
		public function fk_product(){return $this->fk_product;}
		public function fk_order(){return $this->fk_order;}
		public function amount(){return $this->amount;}
		public function value(){return number_format($this->value, 2);}
		public function product(){return $this->product;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setFk_order($fk_order) { $this->fk_order = (int)$fk_order;}
		public function setFk_product($fk_product) { $this->fk_product = (int) $fk_product;}
		public function setAmount($amount) {$this->amount = (int) $amount;}
		public function setValue($value){$this->value= $value;}
		public function setProduct($product){$this->product= $product;}
	}
						