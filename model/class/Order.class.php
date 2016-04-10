<?php

class Order {
	
	private $id_com, // id commande
			$id_c, // id_societe
			$date_crea_com, // date de creation commande
			$date_liv, // date de livraison
			$mode_liv,
			$mode_paiement,
			$commentaire,
			$statut, 				// brouillon (panier), livré, annulé, en préparation -> suivit de la commande.
			
			$list_lc;
	
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

	public function id_com() 	{ return $this->id_com;}
	public function id_c() 	{ return $this->id_c;}
	public function date_crea_com() { return $this->date_crea_com;}
	public function date_liv() 		{ return $this->date_liv;}
	public function mode_liv() 		{ return $this->mode_liv;}
	public function mode_paiement() { return $this->mode_paiement;}
	public function statut() 		{ return $this->statut;}
	public function commentaire() 	{ return $this->commentaire;}
	public function list_lc() 		{ return $this->list_lc;}
	
	public function setId_com($id) { $this->id_com = (int) $id;}
	public function setId_c($id_c) { $this->id_c = (int) $id_c;}
	public function setDate_crea_com($date_crea) { $this->date_crea = $date_crea;}
	public function setDate_liv($date_liv) { $this->date_liv = $date_liv;}
	public function setMode_liv($mode_liv) { $this->mode_liv = $mode_liv;}
	public function setMode_paiement($mode_paiement) { $this->mode_paiement = $mode_paiement;}
	public function setStatut($statut) { $this->statut = $statut;}
	public function setCommentaire($com) { $this->commentaire = $com;}
	
	public function setList_lc($list_lc) {
			$this->list_lc = $list_lc;
	}
}
