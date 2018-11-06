<?php

	class Order {
 /**
   *Order vs database
   * 
   */
   
		private	$id,
						$ref,	// order unique ref
						$fk_customer,
						$customer_type,
						$delivery_address,
						$delivery_zip,
						$delivery_town,
						$delivery_instructions,
						$delivery_week,
						$delivery_date,
						$delivery_cost,
						$order_date,
						$origin, // from web, mail or phone
						$status,
						$total_amount,
						
						//list php
						$list_ol,
						
						//object php
						$customer,		// User, Contact or Organization
						$payment;		// php Object
		
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
		public function ref(){return $this->ref;}
		public function delivery_address(){return $this->delivery_address;}
		public function delivery_town(){return $this->delivery_town;}
		public function delivery_zip(){return $this->delivery_zip;}
		public function delivery_instructions(){return $this->delivery_instructions;}
		public function delivery_date(){return $this->delivery_date;}
		public function delivery_week() {return $this->delivery_week;}
		public function delivery_cost() {return $this->delivery_cost;}
		public function fk_customer(){return $this->fk_customer;}
		public function customer_type(){return $this->customer_type;}
		public function status(){return $this->status;}
		public function order_date(){return $this->order_date;}
		public function list_ol(){return $this->list_ol;}
		public function customer(){return $this->customer;}
		public function total_amount(){return number_format($this->total_amount, 2);}
		public function order_dateTime(){ return new DateTime($this->order_date);}
		public function delivery_dateTime(){ return new dateTime($this->delivery_date);}
		public function origin(){ return $this->origin;}
		public function value() { return number_format($this->getValue(), 2);}
		public function payment() { return $this->payment;}
		
		//setter
		
		public function setId($id) { $this->id = (int) $id;}
		public function setRef($ref) { $this->ref = $ref;}
		public function setDelivery_address($delivery_address) { $this->delivery_address = $delivery_address;}
		public function setDelivery_town($delivery_town) {$this->delivery_town = $delivery_town;}
		public function setDelivery_zip($delivery_zip) {$this->delivery_zip = $delivery_zip;}
		public function setDelivery_instructions($delivery_instructions){$this->delivery_instructions = $delivery_instructions;}
		public function setDelivery_date($delivery_date) {$this->delivery_date = $delivery_date;}
		public function setDelivery_week($delivery_week) {$this->delivery_week = $delivery_week;}
		public function setDelivery_cost($delivery_cost) { $this->delivery_cost = $delivery_cost;}
		public function setFk_customer($fk_customer){$this->fk_customer= (int) $fk_customer;}
		public function setCustomer_type($customer_type){$this->customer_type= $customer_type;}
		public function setStatus($status) { $this->status = $status;}
		public function setOrder_date($order_date){$this->order_date= $order_date;}
		public function setList_ol($list_ol){$this->list_ol= $list_ol;}
		public function setCustomer($customer){$this->customer= $customer;}
		public function setTotal_amount($total_amount){$this->total_amount= $total_amount;}
		public function setOrigin($origin){$this->origin=$origin;}
		public function setPayment($payment){$this->payment=$payment;}
		
		//function
		
		public function getValue() {
			$value=0;
			foreach($this->list_ol as $ol){
				$value += $ol->value();
			}
			$value += $this->delivery_cost;
			return $value;
		}
		public function status_fr(){
			switch($this->status){
				case	"delivered"	:	$value= "Livrée";
				break;
				case	"canceled"	:	$value= "Annulée";
				break;
				case	"Confirmed"	:	$value= "Confirmée";
				break;
				case	"waiting"	:	$value= "En attente";
				break;
				case	"Pending"	:	$value= "En cours";
				break;
			}
			return $value;
		}
	}
						