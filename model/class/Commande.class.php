<?php

	class Commande {
		
		private $id_com,
				$id_pa,
				$id_c,
				$date_crea,
				$date_liv,
				$mode_liv,
				$mode_paiement,
				$total,
				$commentaire,
				$statut, 				// livré, annulé, en préparation -> suivit de la commande.
				
				$list_lc;
		
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);

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

		public function id_com() { return $this->id_com;}
		public function id_pa() { return $this->id_pa;}
		public function id_c() { return $this->id_c;}
		public function date_crea() { 
									$jour = new dateTime($this->day);
									return date_format($jour, 'D d F Y');
		}
		public function date_liv() { 
									$jour = new dateTime($this->day);
									return date_format($jour, 'D d F Y');
		}
		public function mode_liv() { return $this->mode_liv;}
		public function mode_paiement() { return $this->mode_paiement;}
		public function statut() { return $this->statut;}
		public function total() { return $this->total;}
		public function commentaire() { return $this->commentaire();}
		public function panier() { return $this->panier;}
		
	//setter

		public function setId_com($id) { $this->id_com = (int) $id;}
		public function setId_pa($id_pa) { $this->id_pa = (int) $id_pa;}
		public function setId_c($id_c) { $this->id_c = (int) $id_c;}
		public function setDate_liv($date_crea) { $this->date_liv = $date_crea;}
		public function setDate_liv($date_liv) { $this->date_liv = $date_liv;}
		public function setMode_liv($mode_liv) { $this->mode_liv = $mode_liv;}
		public function setMode_paiement($mode_paiement) { $this->mode_paiement = $mode_paiement;}
		public function setStatut($statut) { $this->statut = $statut;}
		public function setCommentaire($com) { $this->commentaore = $com;}
		public function setTotal($total) { $this->total = $total;}
	}