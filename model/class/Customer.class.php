<?php

	class Customer {
		private $id_c, //dolibarr client id
				$name,
				$firstname,
				$email, //UNIQUE, used as login for dolibarr user
				$password,
				$address,
				$zip, // Code postal
				$town,
				$phone,
				$is_adh,
				$id_contact, // dolibarr contact id
				$api_key, // key for api access (after ClientDAO->login($client);)
				$id_user = 0; // dolibarr user id, 0 means anonymous user (not authentified nor created in dolibarr)
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
		public function id_c() {return $this->id_c;}
		public function name() {return $this->name;}
		public function firstname() {return $this->firstname;}
		public function email() {return $this->email;}
		public function password() {return $this->password;}
		public function address() {return $this->address;}
		public function zip() {return $this->zip;}
		public function town() {return $this->town;}
		public function phone() { return $this->phone;}
		public function is_adh() { return $this->is_adh;}
		public function id_contact() { return $this->id_contact;}
		public function id_user() { return $this->id_user;}
		public function api_key() { return $this->api_key;}
		
		//setter
		public function setId_c($id) { $this->id_c = (int) $id;}
		public function setName($name) { $this->name = $name;}
		public function setFirstname($firstname) { $this->firstname = $firstname;}
		public function setEmail($email) { $this->email = $email;}
		public function setPassword($pw) { $this->password = $pw;}
		public function setAddress($address) { $this->address = $address;}
		public function setZip($zip) { $this->zip = $zip;}
		public function setTown($town) { $this->town = $town;}
		public function setPhone($number) { $this->phone = $number;}
		public function setIs_adh($is_adh) { $this->is_adh = $is_adh;}
		public function setId_contact($id_contact) { $this->id_contact = $id_contact;}
		public function setId_user($id_user) { $this->id_user = $id_user;}
		public function setApi_key($api_key) { $this->api_key = $api_key;}
		
	/**
	 *  Obsolete method 
	 *
	 * 	public function get_panierEnCours() {
	 *			$bdd = getBdd();
	 *			$q = $bdd->prepare('SELECT * FROM paniers WHERE id_c = :id_c');
	 *			$q->bindValue(':id_c', $this->id_c, PDO::PARAM_INT);
	 *			$q->execute();
	 *			if ($q->rowCount() == 1){
	 *				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
	 *			{
	 *			  $panier = new Panier ($donnees);
	 *			}
	 *			return $panier;
	 *			}else{ return NULL;}
	 *		}
	 * 	public function get_lastPanierCree(){
	 *		
	 *			$bdd = getBdd();
	 *		
	 *			$q = $bdd->prepare('SELECT * FROM paniers WHERE id_pa = MAX(id_pa) AND id_c = :id_c');
	 *			$q->bindValue(':id_c', $this->id_c, PDO::PARAM_INT);
	 *			$q->execute();
	 *	
	 *			if ($q->rowCount() == 1){
	 *				while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
	 *				$panier = new Panier ($donnees);
	 *			}
	 *				return $panier;
	 *			}
	 *			else { 
	 *			return NULL;
	 *			}
	 *		}
	 */
		
				
	}
