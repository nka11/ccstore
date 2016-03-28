<?php

// La page post permet à l'administrateur de manipuler la base de donnée.
// Elle les formulaires des tables, et traite les données reçu (ou sous-traite)
//		-> Pour formulaire Produit -> necessite de récupérer la liste des catégories et la liste des producteurs pour l'affichage du formulaire
//		-> Elle appelle les fichiers vues : views/form
// La variable $is_form_completed -> BOOLEEN verifie que le formulaire à été remplit.
// La variable $where -> permet d'identifier l'élément spécifique à modifier/supprimer
// La variable $table -> récupère le nom de la table ciblée par l'utilisateur.


	spl_autoload_register(function ($class) {
		include 'model/class/' . $class . '.class.php';
	});
	
	require "model/model.php";
	
	//Ouverture et/ou récupération de session ouverte
	require "ctrl/session.php";
	
	//Chargement du navigateur categorie
	require "ctrl/categories.php"; 								// Le fichier appelle le model qui récupères la liste des catégories.
	
	// Chargement du navigateur
	require 'ctrl/leftNav/ctrl_leftNav.php';
	
	//RECUPERATION DES DONNEES
	
	$is_form_completed	= (isset($_POST['Enregistrer']))	?		true						:	false;			// Le formulaire a été remplit par l'utilisateur.
	
	$table	= (isset($_GET['what']))						? 	htmlentities($_GET['what'])		:	NULL;		// Récup table.
	$action = (isset($_GET['action']))						?	htmlentities($_GET['action'])	:	NULL;		// Recup action pour fonction.
	
	$where	= (isset($_GET['where']))						? 	htmlentities($_GET['where'])	:	NULL; 		// Un identifiant, facultatif.
		
	//	CHARGEMENT DES DONNEES
	
	if($is_form_completed){		require	'ctrl/post/edit'.ucfirst($table).'.php';}							
	else{
		
		switch($action){
			
			case	'ajouter'	:	$t = NULL; $page = 'Ajouter dans TABLE ['.$table.']';
			break;
			case	'modifier'	:	$fn = 'get_'.$table; $t = $fn($where); $page = 'Modifier la TABLE ['.$table.'], ID ['.$where.']';
			break;
		}
	
	if($table=='produit'){	$listCat = getList_categorie(); $listPro = getList_producteur();} 	// On récupère la liste des catégories et des producteurs pour afficher correctement le formulaire 'Produit'
	
	$view = 'views/admin/form/form'.ucfirst($table).'.php';			// Appel du formulaire.
	}
	
	
	//Chargement de la page
	require $view;
	require 'views/gabarit/gabarit.php';