<?php

	class Categorie {
		
		private $id_cat,
				$id_parent, // peut-être nul si pas de categorie parente
				$tag;
				
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
		
		//setter
		
		public function id_cat() { return $this->id_cat;}
		public function id_parent() { return $this->id_parent;}
		public function tag() { return $this->tag;}
		
		//getter
		
		public function setId_cat($id_cat) { $this->id_cat = (int) $id_cat;}
		public function setId_parent($id_parent) { $this->id_parent = (int) $id_parent;}
		public function setTag($tag) { $this->tag = $tag;}
				
		//ctrl
		
		public function ctrlCategorie($tag=NULL){}
		
		
	}