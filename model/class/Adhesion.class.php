<?php

	class Adhesion {
		
		private $id_a,
				$id_c,
				$year,
				$date_paiement,
				$tarif,
				
				$adh;
				
		public function __construct(array $donnees)
		{
			$this->hydrate($donnees);
			$this->adh = getClient($this->id_c);

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
		
		public function id_a() { return $this->id_a;}
		public function id_c() { return $this->id_c;}
		public function year() { return $this->year;}
		public function date_paiement() { 
										$jour = new dateTime($this->day);
									return date_format($jour, 'D d F Y');
		}
		public function tarif() {return $this->tarif;}
		
		public function adh() { return $this->adh;}
		
		//setter
		
		public function setId_a($id_a) { $this->id_a = (int) $id_a;}
		public function setId_c($id_c) { $this->id_c = (int) $id_c;}
		public function setYear($year) { $this->year = $year;}
		public function setDate_paiement($date_paiement) { $this->date_paiement = $date_paiement;}
		public function setTarif($tarif) { $this->tarif = $tarif;}

		
	}