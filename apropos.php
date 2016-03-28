<?php

// 				PAGE A PROPOS...

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
		
		//Chargement de la section principale
		//require 'views/index/view_navCat.php';
		//$view_section	= $view_navCat;
		
		// Chargement du footer
		//require 'views/index/view_footer.php';
		
		//RECUPERATION DES DONNEES
		
		$page	= (isset($_GET['page']))	? htmlentities($_GET['page'])	:	'produit';
		
		
		switch($page){
			
			case 	'about' 		:	$t = get_produit($where)	; $page = $t->titre();		// Chargement des variables à afficher en fonction de la variable $show.
			break;
			case	'producteur'	:	require "ctrl/produits.php"; $page= 'Tous les produits';	// Chargement de la liste des produits.
			break;
			case	'equipe'		:
			break;
		}
		

		// Chargement de la vue correspondante à la requete
		require "views/".$table."/view_".$show.ucfirst($table).".php";
		
		//Chargement de la page
		require 'views/gabarit/gabarit.php';
		
		//PASSAGE DE SESSION