<?php

class Commande extends Panier {
	
	private $id_com,
			//$id_pa,
			//$id_c,
			$date_crea_com,
			$date_liv,
			$mode_liv,
			$mode_paiement,
			$commentaire,
			$statut; 				// livré, annulé, en préparation -> suivit de la commande.
			
			//$list_lc;
	
	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
		parent::hydrate($donnees);
		parent::setList_lc();

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
	//public function id_pa() 	{ return $this->id_pa;}
	//public function id_c() 	{ return $this->id_c;}
	public function date_crea_com() { 
			$jour = new dateTime($this->day);
			return date_format($jour, 'D d F Y');
	}
	public function date_liv() 		{ 
			$jour = new dateTime($this->day);
			return date_format($jour, 'D d F Y');
	}
	public function mode_liv() 		{ return $this->mode_liv;}
	public function mode_paiement() { return $this->mode_paiement;}
	public function statut() 		{ return $this->statut;}
	public function commentaire() 	{ return $this->commentaire;}
	//public function list_lc() 		{ return $this->list_lc;}
	
	/*public function valeurPanier()	{ 
	
		$valeurPanier = 0;
		
		foreach($this->list_lc as $lc) {
			
			$valeurPanier += $lc->valeur();
			
		}
	
		return $valeurPanier;

	}*/
	
	public function tarifLiv()	{
	
		if($this->mode_liv == 'retrait') {
			
			$tarif = ($this->valeur()*20)/100;
			
			if($tarif < 5) {
				
				$tarif = 5;
			}
			elseif($tarif > 10) {
				
				$tarif = 10;
			}
		}
		elseif($this->mode_liv == 'livraison') {
			
			$tarif = ($this->valeur()*40)/100;
			
			if($tarif < 10) {
				
				$tarif = 10;
			}
			elseif($tarif > 20) {
				
				$tarif = 20;
			}
		}
		return $tarif;
	}
	
//setter

	public function setId_com($id) { $this->id_com = (int) $id;}
	//public function setId_pa($id_pa) { $this->id_pa = (int) $id_pa;}
	//public function setId_c($id_c) { $this->id_c = (int) $id_c;}
	public function setDate_crea_com($date_crea) { $this->date_crea = $date_crea;}
	public function setDate_liv($date_liv) { $this->date_liv = $date_liv;}
	public function setMode_liv($mode_liv) { $this->mode_liv = $mode_liv;}
	public function setMode_paiement($mode_paiement) { $this->mode_paiement = $mode_paiement;}
	public function setStatut($statut) { $this->statut = $statut;}
	public function setCommentaire($com) { $this->commentaire = $com;}
	
	/*public function setList_lc() {
			
			$bdd = getBdd();
			
			$q = $bdd->prepare('SELECT * FROM lignes_commande lc LEFT JOIN produits p ON lc.id_p = p.id_p WHERE id_pa = :id_pa');
			$q->bindValue(':id_pa', $this->id_pa, PDO::PARAM_INT);
		
			$q->execute();
		
			if ($q->rowCount() > 0){
			
			//print_r($donnees);exit();
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $this->list_lc[] = new Ligne_commande ($donnees);
				}
			
			}else{ $this->list_lc = array();}
				
	}*/
	
//others

	public function calculTotal() {
		
		$total = $this->valeur()+$this->tarifLiv();
		return $total;
		
	}
	
	public function clearPanier() {
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM paniers WHERE id_pa = '.$this->id_pa());
				
	}
}