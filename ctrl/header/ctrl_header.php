<?php

// CTRL-HEADER : charge toutes les vues necessaire à l'affichage du header

	// Formulaire de connexion
	
	if($session_visitor_open){
		
		require 'views/header/view_formConnexion.php';
	
	}elseif($session_client_open){
	
		require 'views/client/view_boxClient.php';
	
	}elseif($session_admin_open){
		
		require 'views/admin/view_boxAdmin.php';
	}
	
	// Chargement du navigateur
	require 'views/header/nav/view_mainNav.php';
	
	require 'views/header/view_header.php';