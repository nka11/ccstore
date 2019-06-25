<?php

	class OrderPayment {
 /**
   * OrderPayment vs database
   * Means Payment from private (users or customers)
   * 
   */
   
		private	$id,
				$fk_order,
				$day,
				$amount,
				$method;
		
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
		public function fk_order(){return $this->fk_order;}
		public function day(){return $this->day;}
		public function day_dateTime(){ return new DateTime($this->day);}
		public function method(){return $this->method;}
		
		//setter
		
		public function setId($id) { $this->id= (int) $id;}
		public function setFk_order($fk_order) { $this->fk_order= (int)$fk_order;}
		public function setDay($day) {$this->day= $day;}
		public function setMethod($method){$this->method= $method;}
	}
						