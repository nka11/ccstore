<?php

	spl_autoload_register(function ($class) {
		include 'model/class/' . $class . '.class.php';
	});
	
		require "model/model.php";
		
		// Ouverture et/ou récupération de session ouverte
		require "ctrl/session.php";
		
		// Chargement de la liste des catégories.
		require "ctrl/categories.php"; // Le fichier appelle le model qui récupères la liste des catégories.
		
		// Récupération et création du panier client
		require 'ctrl/panier/ctrl_panier.php';
		
		// Chargement du header
		require 'ctrl/header/ctrl_header.php';
		
		//Chargement de la section principale
		//require 'views/index/view_navCat.php';
		//$view_section	= $view_navCat;
		
		// Chargement du footer
		require 'views/footer/view_footer.php';		
		
		// Chargement de la vue correspondante à la requete
		//require "views/".$show."/section/view_".$show.ucfirst($table).".php";
		
		//Chargement de la page
		require 'views/gabarit/gabaritIndex.php';
		
		//PASSAGE DE SESSION