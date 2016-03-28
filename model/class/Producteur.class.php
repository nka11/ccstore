<?php

	class Producteur {
		
		private $id_pro,
				$denom, // Dénomination
				$titre, // SAS, particulier, etc
				$adresse,
				$departement,
				$telephone,
				$description;
				
		
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
		
		public function id_pro() {return $this->id_pro;}
		public function denom() {return $this->denom;}
		public function titre() {return $this->titre;}
		public function adresse() {return $this->adresse;}
		public function departement() {return $this->departement;}
		public function telephone() { return $this->telephone;}
		public function description() {return $this->description;}
				
			
		//setter
		
		public function setId_pro($id) { $this->id_pro = (int) $id;}
		public function setDenom($denom) { $this->denom = $denom;}
		public function setTitre($titre) { $this->titre = $titre;}
		public function setAdresse($adresse) { $this->adresse = $adresse;}
		public function setDepartement($departement) { $this->departement = $departement;}
		public function setTelephone($telephone) { $this->telephone = $telephone;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		
	}