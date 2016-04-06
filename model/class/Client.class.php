<?php

	class Client {
		
		private $id_c,
				$nom,
				$prenom,
				$email, //UNIQUE
				$mdp,
				$adresse,
				$code_postal,
				$ville,
				$departement,
				$telephone,
        $is_adh,
        $id_contact;
				
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);

		}
		
		public function hydrate(array $donnees)
		{
			foreach ($donnees as $key => $value)
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
		public function nom() {return $this->nom;}
		public function prenom() {return $this->prenom;}
		public function email() {return $this->email;}
		public function mdp() {return $this->mdp;}
		public function adresse() {return $this->adresse;}
		public function code_postal() {return $this->code_postal;}
		public function ville() {return $this->ville;}
		public function departement() {return $this->departement;}
		public function telephone() { return $this->telephone;}
		public function is_adh() { return $this->is_adh;}
		public function id_contact() { return $this->id_contact;}
		
		public function get_panierEnCours() {
			
			$bdd = getBdd();
			
			$q = $bdd->prepare('SELECT * FROM paniers WHERE id_c = :id_c');
			$q->bindValue(':id_c', $this->id_c, PDO::PARAM_INT);
			$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $panier = new Panier ($donnees);
				}
			return $panier;
			
		}else{ return NULL;}
			
			
		}
		
		
		//setter
		
		public function setId_c($id) { $this->id_c = (int) $id;}
		public function setNom($nom) { $this->nom = $nom;}
		public function setPrenom($prenom) { $this->prenom = $prenom;}
		public function setEmail($email) { $this->email = $email;}
		public function setMdp($mdp) { $this->mdp = $mdp;}
		public function setAdresse($adresse) { $this->adresse = $adresse;}
		public function setCode_postal($cp) { $this->code_postal = $cp;}
		public function setVille($ville) { $this->ville = $ville;}
		public function setDepartement($departement) { $this->departement = $departement;}
		public function setTelephone($telephone) { $this->telephone = $telephone;}
		public function setIs_adh($is_adh) { $this->is_adh = $is_adh;}
		public function setId_contact($id_contact) { $this->is_adh = $id_contact;}
				
	}
