<?php

	class Page {
		
		private $id_page,
				$titre,
				$description,
				$keywords,
				$subject;
				
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
		
		public function id_page() { return $this->id_page;}
		public function titre() { return $this->titre;}
		public function description() { return $this->description;}
		public function keywords() { return $this->keywords;}
		public function subject() { return $this->subject;}
		
		//setter
		
		public function setId_page($id_page) { $this->id_page = (int) $id_page;}
		public function setTitre($titre) { $this->titre = $titre;}
		public function setDescription($desc) { $this->description = $desc;}
		public function setKeywords($keywords) { $this->keywords = $keywords;}
		public function setSubject($subject) { $this->subject = $subject;}
				
		// CTRLER
		
		public function ctrl_index($categorie=NULL){ // Chargement de la liste des produits
			
			$list_produit = (!empty($categorie))	?	getList_produit($categorie) : getList_produit(); // Si une categorie est fournie, on liste en fn.
			$produit = (!empty($produit)) 			? 	get_produit($produit)		: NULL;					//Si un produit en particulier est à afficher

		}
		
		
		
		
		
		
		
		
		
	}