<?php

// La page admin est appelé par les liens du menu administrateur ( fichier : headband.php);
// Elle peut être appelé par le fichier de traitement des formulaires (fichier : editTable) et recevoir la variable : '$formAnswer';
// Elle peut afficher les listes des tables, ou le détail d'un produit en particulier.
//		-> Elle appelle les fichiers vues : views/detail et views/list
// La variable $show -> réceptionne la demande de l'utilisateur ( liste / détail)
// La variable $where -> permet d'identifier l'élément spécifique à afficher
// La variable $table -> récupère le nom de la table ciblée par l'utilisateur.


	spl_autoload_register(function ($class) {
		include 'model/class/' . $class . '.class.php';
	});
	
	require "model/model.php";
	
	//Ouverture et/ou récupération de session ouverte
	require "ctrl/session.php";
	
	// Chargement du panier (uniquement dans le cas de la page de formulaire de connexion)
	if($session_client_open || $session_visitor_open){
	require 'ctrl/panier/ctrl_panier.php';
	}
	
	//Chargement du navigateur categorie
	require "ctrl/categories.php";
	
	// Chargement du navigateur
	require 'ctrl/leftNav/ctrl_leftNav.php';
	
	//RECUPERATION DES DONNEES
	
	$table	= (isset($_GET['what']))	? htmlentities($_GET['what'])	:	NULL;
	$show	= (isset($_GET['show']))	? htmlentities($_GET['show'])	:	NULL;		// Vaut 'list' ou 'detail'
	$where	= (isset($_GET['where']))	? htmlentities($_GET['where'])	:	NULL; 		// Un identifiant
	$formAnswer = (isset($_GET['formAnswer'])) ? htmlentities($_GET['formAnswer']) : NULL;	//Si un formulaire à généré une erreur.
	
	//	CHARGEMENT DES DONNEES
	
	switch($show){
		
		case	'detail'	:	$fn		= 'get_'.$table; $page = 'Fiche TABLE ['.$table.'], ID ['.$where.']';
		break;
		case	'list'		:	$fn		= 'getList_'.$table; $page = 'liste TABLE ['.$table.']';
		break;
		default				:	$page	= 'Authentification requise';
		break;
	}
	
	if(!$session_admin_open){
		
		$view =	'views/admin/form/view_formAdminConnexion.php';
	}else{
	
		$t = $fn($where);																// Chargement de la cible (peut être une liste ou un obbjet)
		$view = 'views/admin/'.$show.'/'.$show.ucfirst($table).'.php';					// Déclaration du chemin de la vue correspondante.
	}
	
	//Chargement de la page

	require $view;
	require 'views/gabarit/gabarit.php';