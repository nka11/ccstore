<?php

class Product {
  /**
   * Représente un produit vs model dolibarr :
   * Un produit peut avoir plusieurs producteurs
   * Un produit peut avoir plusieures catégories
   *
   */
		
		private $id_p,
				$titre,
				$prix_achat,
				$prix_vente,
				$tva,
				$description,
				$is_active,
				$img,
				
				//objets : 
				
				$categories,
				$producteurs;
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);
		}
    /**
     * loads data from a db record
     */
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
		
		public function id_p() {return $this->id_p;}
		public function id() {return $this->id_p;}
		public function titre() {return $this->titre;}
		public function prix_achat() {return $this->prix_achat;}
		public function prix_vente() {return $this->prix_vente;}
		public function tva() {return $this->tva;}
		public function description() {return $this->description;}
		public function is_active() { return $this->is_active;}
		public function img() {return $this->img;}
		
		public function categories() { return $this->categories;}
		public function producteurs() { return $this->producteurs;}
		
		
		//setter
		
		public function setId_p($id) { $this->id_p = (int) $id;}
		public function setTitre($titre) { $this->titre = $titre;}
		public function setPrix_achat($prix_a) { $this->prix_achat =  $prix_a;}
		public function setPrix_vente($prix_v) { $this->prix_vente =  $prix_v;}
		public function setCategories($categories) { $this->categories =  $categories;}
		public function setTva($tva) { $this->tva =  $tva;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		public function setIs_active($is_active) { $this->is_active = $is_active;}
		public function setImg($img) { $this->img = $img;}
		
		
		// Html_formulaire
		
		public function html_form()	{ include	'views/form/produit.php';}
		
		
		
	}
