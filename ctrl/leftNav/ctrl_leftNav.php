<?php

// CTRL-leftNav prépare l'affichage du navigateur principale de gauche.
// Il appelle la boxUser correspondant à la session ouverte.
// Il appelle l'affichage spécifique de la liste des Catégories pour le navigateur principale. ( Si nécessaire -> !Cas d'une session administrateur)
// Appelle l'affichage du menu administrateur ( Si nécessaire -> !Cas d'une session client ou visiteur)
// Appelle la vue générale : view_leftNav

	
	require 'ctrl/boxUser/ctrl_boxUser.php';			// --> Gestion de la vue boxUser.
	
	require 'views/categorie/view_listCat.php';			//	--> Gestion de l'affichage de la list des categories
	require 'views/panier/view_boxPanier.php';			// --> Gestio de l'affichage du panier
	require 'views/leftNav/view_leftNav.php';			// --> Vue générale du navigateur principal.