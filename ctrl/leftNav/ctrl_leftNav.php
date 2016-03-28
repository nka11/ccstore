<?php

// CTRL-leftNav prépare l'affichage du navigateur principale de gauche.
// Il appelle la boxUser correspondant à la session ouverte.
// Il appelle l'affichage spécifique de la liste des Catégories pour le navigateur principale. ( Si nécessaire -> !Cas d'une session administrateur)
// Appelle l'affichage du menu administrateur ( Si nécessaire -> !Cas d'une session client ou visiteur)
// Appelle la vue générale : view_leftNav

	// Chargement de la vue $view_leftNav_boxUser
	if($session_visitor_open){
		
		require 'views/form/view_formConnexion.php';					//--> Formulaire de connexion       |	--> $view_leftNav_boxUser
		require 'views/categorie/view_listCat.php';						//--> Listing des catégories        |	--> $view_leftNav_listCat
		require 'views/panier/view_boxPanier.php';						//--> Affichage du panier			|	--> $view_leftNav_panier
		
	}elseif($session_client_open){
		
		require 'views/client/view_boxClient.php';						// Adresse email du client + acces profil + bouton déconnexion		|	--> $view_leftNav_boxUser
		require 'views/categorie/view_listCat.php';						// Liste des catégories												| 	--> $view_leftNav_listCat
		require 'views/panier/view_boxPanier.php';						// Récap panier (nbre articles+Valeur) + (hidden : listCat)			|	--> $view_leftNav_panier
		
	}elseif($session_admin_open){
		
		require 'views/admin/view_boxAdmin.php';				//--> Affichage du pseudo admin + Accès modif Profil + bouton déconnexion
		require 'views/categorie/view_listCat.php';				//--> Affichage des catégories + formulaire ajouter une catégorie.
		require 'views/headBand.php';

	}
	
	require 'views/leftNav/view_leftNav.php';