<?php
require_once("./model/class/Contact.class.php");
	class OrganizationContact extends Contact{
 /**
   * OrganizationContact vs database
   * 
   */
		private	$fk_organization,
				$fn,

				// PHP Object
				$organization;
		
		/* TO BE DELETED ?
			public function __construct(array $data)
			{
				$this->hydrate($data);
			}
		*/
		//getter
		
		public function fk_organization(){return $this->fk_organization;}
		public function fn(){return $this->fn;}
		public function organization(){return $this->organization;}
		
		//setter
		
		public function setFk_organization($fk_organization) {$this->fk_organization = $fk_organization;}
		public function setFn($fn) {$this->fn = $fn;}
		public function setOrganization($organization){$this->organization = $organization;}
	}