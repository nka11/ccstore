<?php

// CTRL_PANIER :  	-> Chargement du panier "en cours" du client (depuis $_SESSION['panier']) -> $_SESSSION['panier'] inexistant, ouverture de session panier.
//					-> Récupération des données ajouts/ modification / suppression d'un article au panier.
//					-> Enregistrement du panier dans $_SESSION['panier']
	
	$panier		= (!empty($_SESSION['panier']) AND !isset($_GET['vider']))		?	$_SESSION['panier']	:	new Panier( array(	'id_pa'		=>	0,
																																'id_c'		=> 	$user->id_c(),
																																'date_crea'	=>	date('d-m-Y'),
																																'montant'	=>	0,
																																'list_lc'	=>	array()));
	
	if(isset($_GET['vider'])){
		
		if($session_client_open){
			
			$target		=	$user->get_panierEnCours();				// Pose le pblm des paniers deja commandés... attribut "statut" au panier -> valeur "en cours" "valide".
			
			if (!empty($target)) {
				
				delete_panier($target);								// Echappe le cas dou le panier demandé n'existe plus (supprimé manuellement en base par exemple.)
			}
		}
		
		
	}
	
	
	// Recuperation en cas de suppression de ligne : 
	if(isset($_GET['supLigne_com'])){
		
		$id_lc		=	(isset($_GET['id_lc'])) 	?	intval($_GET['id_lc'])			:	NULL;
		$ligne_com	= (!empty($id_lc))				?	$panier->get_ligne_com($id_lc)	:	NULL;
		if(!empty($ligne_com)){	$panier->delete_ligne_com($ligne_com);}
		
		$panier = get_panier($panier->id_pa());		// Mise à jour du panier apres manipulation.
	}
	
	
	// Reception d'un article à ajouter/modifier au panier
	if(isset($_POST['submitToPanier'])){
		
		$action	=	(!empty($_POST['action']))		?	htmlentities($_POST['action'])	:	NULL;
		$qte	=	(!empty($_POST['quantite']))	?	intval($_POST['quantite'])	:	NULL;
		$id_p	=	(!empty($_POST['id_p']))		?	intval($_POST['id_p'])		:	NULL;
		$id_lc	=	(isset($_POST['id_lc']))		?	intval($_POST['id_lc'])		:	NULL;
		$nomVar = 	array('qte', 'id_p');
		$e		=	false;
		
		foreach($nomVar as $var){	$e	=	(!empty($$var))	?	false	:	true;}			// Verification erreur formulaire.

		
		if(!$e){			// Si pas d'erreur de remplissage du formulaire, Création de la nouvelle ligne.
			
			if($session_client_open && $panier->id_pa() == 0){
				
				add_panier($panier);			// Si une session Client est ouverte, mais qu'il n'existe pas de panier en cours, on ajoute un nouveau panier en BDD
				
				$panier = $user->get_panierEnCours();	// Puis on récupère le panier en cours en base de donnée. Permet de renseigner l'id_pa correcte.
				
			}	
			
			
			$pro_lc =	get_produit($id_p);			// Chargement du produit à ajouter au panier
				
			$ligne_com	=	new Ligne_commande(array(	'id_p'			=> 	$pro_lc->id_p(),
														'titre'			=> 	$pro_lc->titre(),
														'prix_achat'	=> 	$pro_lc->prix_achat(),
														'prix_vente'	=> 	$pro_lc->prix_vente(),
														'tva'			=> 	$pro_lc->tva(),
														'id_producteur' => 	$pro_lc->id_producteur(),
														'tag_cat'		=> 	$pro_lc->tag_cat(),
														'description'	=> 	$pro_lc->description(),
														'is_active'		=> 	$pro_lc->is_active(),
														'img'			=> 	$pro_lc->img(),			
														'id_lc'			=>	$id_lc,
														'id_pa'			=>	$panier->id_pa(),
														'quantite'		=>	$qte));
				
				// EN FONCTION DE LA SESSION OUVERTE : enregistrmement des lignes de commandes en base de donnée.
				
			if($session_visitor_open){ // CAS DUN VISITEUR, SIMPLE AJOUT DE LA LIGNE DANS LA SESSION PANIER
					
				switch($action){
					
					case	'ajouter'	:	$panier->addIn_lc($ligne_com);
					break;
					case	'modifier'	:	$panier->setIn_lc($ligne_com);
					break;	
				}
		
					
			}elseif($session_client_open){
					
					$panier->add_ligne_commande($ligne_com);
					$panier = get_panier($panier->id_pa());		// Mise à jour du panier apres manipulation.		

			}	
		}
	}
	
	// Chargement de la vue panier
	require 'views/panier/view_panier.php';

	//$_SESSION['panier'] = $panier;