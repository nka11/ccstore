<?php

	class Produit {
		
		private $id_p,
				$titre,
				$prix_achat,
				$prix_vente,
				$tva,
				$id_producteur,
				$tag_cat,
				$description,
				$is_active,
				$img,
				
				//objets : 
				
				$categorie,
				$producteur;
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);
			$this->categorie = get_categorie($this->tag_cat());
			$this->producteur = get_producteur($this->id_producteur());
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
		
		public function id_p() {return $this->id_p;}
		public function titre() {return $this->titre;}
		public function prix_achat() {return $this->prix_achat;}
		public function prix_vente() {return $this->prix_vente;}
		public function tva() {return $this->tva;}
		public function id_producteur() { return $this->id_producteur;}
		public function tag_cat() { return $this->tag_cat;}
		public function description() {return $this->description;}
		public function is_active() { return $this->is_active;}
		public function img() {return $this->img;}
		
		public function categorie() { return $this->categorie;}
		public function producteur() { return $this->producteur;}
		
		
		//setter
		
		public function setId_p($id) { $this->id_p = (int) $id;}
		public function setTitre($titre) { $this->titre = $titre;}
		public function setPrix_achat($prix_a) { $this->prix_achat =  $prix_a;}
		public function setPrix_vente($prix_v) { $this->prix_vente =  $prix_v;}
		public function setTva($tva) { $this->tva =  $tva;}
		public function setId_producteur($id_producteur) { $this->id_producteur = (int) $id_producteur;}
		public function setTag_cat($tag_cat) { $this->tag_cat = $tag_cat;}
		public function setDescription($description) { 
			if (is_string($description))
				{$this->description = $description;}
		}
		public function setIs_active($is_active) { $this->is_active = $is_active;}
		public function setImg($img) { $this->img = $img;}
		
		
		// Html_formulaire
		
		public function html_form()	{ include	'views/form/produit.php';}
		
		
		
	}