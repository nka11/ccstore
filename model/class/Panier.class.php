<?php

	class  Panier  {
		
		private $id_pa,
				$id_c,
				$date_crea_pa,
				
				$list_lc;
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);
			$this->setList_lc();

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

		public function id_pa() { return $this->id_pa;}
		public function id_c() { return $this->id_c;}
		public function date_crea() { 
									$jour = new dateTime($this->date_crea);
									return date_format($jour, 'Y-d-D');
		}
		public function list_lc() { return $this->list_lc;}
		public function statut() { return $this->statut;}
		
		public function getList_ligne_commande(){	
		
			$bdd = getBdd();
			
			$q = $bdd->prepare('SELECT * FROM lignes_commande WHERE id_pa = :id_pa');
			$q->bindValue(':id_pa', $this->id_pa, PDO::PARAM_INT);
		
			$q->execute();
		
			if ($q->rowCount() > 0){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $ligne_com[] = new Ligne_commande ($donnees);
				}
				
				return $ligne_com;
			
			}else{ return NULL;}
		}
		
		public function add_ligne_commande($lc){
			
				
			$bdd = getBdd();
		
			$q = $bdd->prepare('INSERT INTO lignes_commande SET id_pa= :id_pa, id_p= :id_p, quantite= :quantite');
			// Assignation des valeurs à la requête.
			$q->bindValue(':id_pa', $lc->id_pa(), PDO::PARAM_INT);
			$q->bindValue('id_p', $lc->id_p(), PDO::PARAM_INT);
			$q->bindValue(':quantite', $lc->quantite(), PDO::PARAM_INT);
						
			// Exécution de la requête.
			$q->execute();
			
		}
		
		public function set_ligne_commande($lc){
			
			$bdd = getBdd();
		
			$q = $bdd->prepare('UPDATE lignes_commande SET id_pa= :id_pa, id_p= :id_p, quantite= :quantite WHERE id_lc= :id_lc');
			// Assignation des valeurs à la requête.
			$q->bindValue(':id_pa', $lc->id_pa(), PDO::PARAM_INT);
			$q->bindValue('id_p', $lc->id_p(), PDO::PARAM_INT);
			$q->bindValue(':quantite', $lc->quantite(), PDO::PARAM_INT);
			$q->bindValue('id_lc', $lc->id_lc(), PDO::PARAM_INT);
						
			// Exécution de la requête.
			$q->execute();
			
		}
		
		public function delete_ligne_commande($lc){
			
			$bdd = getBdd();
			$bdd->exec('DELETE FROM lignes_commande WHERE id_lc = '.$lc->id_lc());
			
		}
		
		public function valeur() { 
			
			$value = 0;
			
			foreach($this->list_lc as $lc){
				
				$value += $lc->valeur();
					
			}
			
			return $value;
			
		}
		
		public function prevTarifLiv($modeLiv) {
			
			$tarif = ($modeLiv == 'livraison')	?	($this->valeur()*40)/100	:	($this->valeur()*20)/100;
			
			if($modeLiv == 'livraison' && $tarif < 10) {
				
				$tarif = 10;
			}
			elseif($modeLiv == 'livraison' && $tarif>20) {
				
				$tarif = 20;
			}
			elseif($modeLiv == 'retrait' && $tarif<5) {
				
				$tarif = 5;
			}
			elseif($modeLiv == 'retrait' && $tarif>10) {
				
				$tarif = 10;
			}
			return $tarif;
		}
		
	//setter

		public function setId_pa($id_pa) { $this->id_pa = (int) $id_pa;}
		public function setId_c($id_c) { $this->id_c = (int) $id_c;}
		public function setDate_crea($date_crea) { $this->date_crea = $date_crea;}
		public function setList_lc() {
			
			$bdd = getBdd();
			
			$q = $bdd->prepare('SELECT * FROM lignes_commande lc LEFT JOIN produits p ON lc.id_p = p.id_p WHERE id_pa = :id_pa');
			$q->bindValue(':id_pa', $this->id_pa, PDO::PARAM_INT);
		
			$q->execute();
		
			if ($q->rowCount() > 0){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $this->list_lc[] = new Ligne_commande ($donnees);
				}
			
			}else{ $this->list_lc = array();}
			
				
		}
				
		public function addIn_lc($lc){ $this->list_lc[] = $lc;}

			
			
			
			
}