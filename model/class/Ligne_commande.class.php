<?php

	class Ligne_commande extends Produit {
		
		private $id_lc,
				$id_pa,
				$quantite,
				
				$valeur;
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);
			parent::hydrate($donnees);
			$this->setValeur();
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

		public function id_lc() { return $this->id_lc;}
		public function id_pa() { return $this->id_pa;}
		public function quantite() { return $this->quantite;}
		public function valeur() { return $this->valeur;}
		
	//setter

		public function setId_lc($id) { $this->id_lc = (int) $id;}
		public function setId_pa($id_pa) { $this->id_pa = $id_pa;}
		public function setQuantite($quantite) { $this->quantite = $quantite;}
		public function setValeur() { $this->valeur = $this->prix_vente() * $this->quantite;}
	}