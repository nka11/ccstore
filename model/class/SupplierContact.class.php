<?php
require_once("./model/class/Contact.class.php");
	class SupplierContact extends Contact{
 /**
   * OrganizationContact vs database
   * 
   */
		private	$fk_supplier,

				// PHP Object
				$supplier;
			
		//getter
		
		public function fk_supplier(){return $this->fk_supplier;}
		public function supplier(){return $this->supplier;}
		
		//setter
		
		public function setFk_supplier($fk_supplier) {$this->fk_supplier = $fk_supplier;}
		public function setSupplier($supplier){$this->supplier = $supplier;}
	}