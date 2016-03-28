<?php

// 				PAGE COMMANDE

// Si l'utilisateur n'a jamais commandé, (pas client), on affiche le formulaire d'inscription

	spl_autoload_register(function ($class) {
		include 'model/class/' . $class . '.class.php';
	});
	
		require "model/model.php";
		
		// Ouverture et/ou récupération de session ouverte ( chargement du User / ouverte-fermeture de session)
		require "ctrl/session.php";
		
		// Chargement de la liste des catégories.
		require "ctrl/categories.php"; 						// Le fichier appelle le model qui récupères la liste des catégories.
		
		// Récupération et création du panier client
		require 'ctrl/panier/ctrl_panier.php';
		
		// Chargement du navigateur
		require 'ctrl/leftNav/ctrl_leftNav.php';
		
		//RECUPERATION DES DONNEES
		
		$step						= (isset($_GET['step']))				?	htmlentities($_GET['step'])	:	NULL;
		$is_form_completed			= (isset($_POST['Enregistrer']))		?	true	:	false;			// Un nouveau client à rempli le formulaire.

		$is_commande_validated		= (isset($_POST['Valider']))			?	true	:	false;			// Une nouvelle commande à été validée.

		$formAnswer = (isset($_GET['formAnswer'])) ? htmlentities($_GET['formAnswer']) : NULL;				//Si un formulaire à généré une erreur.
		
		
		// TRAITEMENT DES DONNEES ET APPEL DES VUES
		
		if($step == 'visitor'){	// PARAMETRE CLIENT
				
				if(!$is_formClient_completed){			// Si utilisateur inconnu et pas de données "nouveau client" reçues
					
					require ' views/form/view_formClient.php';				// Chargement vue du formulaire "nouveau client"
					
				}elseif($is_form_completed){			//Sinon, si formulaire "nouveau client" rempli.
					
					require 'ctrl/post/editClient.php';						// Traitement du formulaire "nouveau client"
				}
		}elseif($step == 'client'){						//Si Client deja renseigné.
			
				if(!$is_form_completed){					// Sinon si CLIENT IDENTIFIE et pas de renseignement sur method livraison et paiement
					
					require 'views/form/view_formCommande.php';								// Chargement de l'affichage du formulaire	parametrage commande.		
				}elseif($is_form_completed){
					
					require 'ctrl/post/editCommande.php';
					
				}
		}elseif($step == 'paiement'){
			
				if($is_commande_validated){
					
					add_commande($panier);					// Si la commande est validée on l'ajoute à la base de donnée.
					if( $panier->mode_paiement() == 'En ligne'){ /* REDIRECTION VERS PAYBOX/ paypal*/}
					else{	require 'view_confirmationCommande.php';}
				}else(!$is_commande_validated){
					
					require 'views/commande/view_recapCommande.php';
				}
		}elseif($step == 'annuler'){
			
			header('Location: boutique.php?$formAnswer=Commande annulée&show=list');exit();
		}

		
		//Chargement de la page
		require 'views/gabarit/gabarit.php';
		
		//PASSAGE DE SESSION