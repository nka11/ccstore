<?php

// 				PAGE BOUTIQUE

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

		// Chargement de la box_ValidCommand
		require 'ctrl/boxValidCommand/ctrl_boxValidCommand.php';
		
		//RECUPERATION DES DONNEES
		
		$table	= (isset($_GET['what']))	? htmlentities($_GET['what'])	:	'produit';
		$show	= (isset($_GET['show']))	? htmlentities($_GET['show'])	:	'list';		// Vaut 'list' ou 'detail'
		$where	= (isset($_GET['where']))	? htmlentities($_GET['where'])	:	NULL; 		// Un identifiant
		$reqCat = (isset($_GET['categorie'])) ? get_categorie(htmlentities($_GET['categorie'])) : NULL;	// Un tag categorie.
		$formAnswer = (isset($_GET['formAnswer'])) ? htmlentities($_GET['formAnswer']) : NULL;	//Si un formulaire à généré une erreur.
		
		
		
		switch($show){
			
			case 	'detail' 	:	$t = get_produit($where)	; $page = $t->titre();		// Chargement des variables à afficher en fonction de la variable $show.
			break;
			case	'list'		:	require "ctrl/produits.php"; $page= 'Tous les produits';	// Chargement de la liste des produits.
			break;
			
		}
		

		// Chargement de la vue correspondante à la requete
		require "views/".$table."/view_".$show.ucfirst($table).".php";
		
		//Chargement de la page
		require 'views/gabarit/gabarit.php';
		
		//PASSAGE DE SESSION
		
$_SESSION['panier'] = (!empty($panier))	?	$panier	:	NULL;