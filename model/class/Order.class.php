<?php

class Order {
	
	private $id_com = NULL, // id commande
			$id_c, // id_societe
			$refcom = NULL, // reference commande dolibarr
			$date_crea_com, // date de creation commande
			$date_liv, // date de livraison
			$mode_liv,
			$mode_paiement, // 6 - CB, 7 - CHQ
			$cond_paiement, // condition de paiement (Commande - 6/Livraison - 7)
			$commentaire,
			$statut = 0, 				// brouillon (panier) - 0, livré, annulé, en préparation -> suivit de la commande.
			
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
	public function cond_paiement() 		{ return $this->cond_paiement;}
	public function refcom() 		{ return $this->refcom;}
	
	public function setId_com($id) { $this->id_com = (int) $id;}
	public function setId_c($id_c) { $this->id_c = (int) $id_c;}
	public function setRefcom($refcom) { $this->refcom = $refcom;}
	public function setDate_crea_com($date_crea) { $this->date_crea = $date_crea;}
	public function setDate_liv($date_liv) { $this->date_liv = $date_liv;}
	public function setMode_liv($mode_liv) { $this->mode_liv = (int)$mode_liv;}
	public function setMode_paiement($mode_paiement) { $this->mode_paiement = (int)$mode_paiement;}
	public function setStatut($statut) { $this->statut = $statut;}
	public function setCond_paiement($cond_paiement) { $this->cond_paiement = (int)$cond_paiement;}
	public function setCommentaire($com) { $this->commentaire = $com;}
  public function setList_lc($list_lc) { $this->list_lc = $list_lc; }

  public function addLigne_c($orderline) { array_push($this->list_lc, $orderline);}

  public function getLine(int $lid) { 
    $findLine = function($orderLine) use (&$lid) {
      return ((int) $orderLine->id_lc()) == $lid;
    };
    $resarray = array_filter($this->list_lc, $findLine);
    if (count($resarray) == 1) return $resarray[array_keys($resarray)[0]];
    if (count($resarray) == 0) return null;
    throw new Exception("Integrity error, more than one line with same Id");
  }
}
