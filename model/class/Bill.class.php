<?php
class Bill {
	/**
	 *Order vs database
	 */
	private		$id,
				$number,
				$supplier_code,
				$issue, 		// date of issue
				$amount,
				$fk_payment,
				
				//php object
				$payment,
				$merchandises,	// bill VS stock : list of merchandises
				$supplier;		// Object Supplier
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
	//getters
	public function id() {return $this->id;}
	public function supplier_code() {return $this->supplier_code;}
	public function number() {return $this->number;}
	public function issue() {return $this->issue;}
	public function amount() {return $this->amount;}
	public function fk_payment() {return $this->fk_payment();}
	public function issue_datetime() {return new dateTime($this->issue);}
	public function payment() {return $this->payment;}
	public function merchandises() {return $this->merchandises;}
	public function supplier() {return $this->supplier;}
	//setters
	public function setId($id) { $this->id= (int) $id;}
	public function setSupplier_code($code) { $this->supplier_code= $code;}
	public function setNumber($nbr) { $this->number= $nbr;}
	public function setAmount($amount) { $this->amount= $amount;}
	public function setIssue($issue) { $this->issue= $issue;}
	public function setFk_payment($paymentID) { $this->fk_payment= (int) $paymentID;}
	public function setPayment($payment) {$this->payment= $payment;}
	public function setMerchandises($merchandises) { $this->merchandises= $merchandises;}
	public function setSupplier($supplier) { $this->supplier= $supplier;}
}