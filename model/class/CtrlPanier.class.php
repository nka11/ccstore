<?php

class CtrlPanier	{
	
	private $bdd;
	public function __construct($bdd){
		
		$this->setBdd($bdd);
		
	}
	public function setDb(PDO $db)
	{
		$this->bdd = $db;
	}
	
	public function vider(Panier $panier)	{
		
		if(!empty($panier))	{
			
			delete_panier($panier);
		}
		
	}
	
	
}