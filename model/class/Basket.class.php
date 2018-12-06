<?php

	class Basket {
 /**
   * Php object. Managed by StoreController.
   * 
   */
   
		private	$articles;	// article list
		
		public function __construct()
		{
			$this->articles= array();
		}
		
		//getter
		
		public function total_amount(){
			$total_amount= 0;
			foreach( $this->articles as $article){
				$total_amount+= number_format($article['product']->price()*number_format($article['amount'], 2), 2);
			}
			return $total_amount;
		}
		
		//fn
		
		public function add_article($article){
			$completed= false;
			// check if article already exist.
			foreach($this->articles as $a){
				if($a['product']->id() == $article['product']->id()) $a['amount']= $a['amount'] + $article['amount'];
				$completed= true;
			}
			if(!$completed)	$this->articles[]= $article;
		}
		public function delete_article($id_product){
			foreach( $this->articles as $key=>$article){
				if( $article['product']->id() == $id_product) unset($this->articles[$key]);
			}
		}
	}
						