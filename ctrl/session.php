<?php
//	Le fichier SESSION est appelé par toutes les pages.
// Il lance l'authentification en cas de demande de connexion.
// Il gère le contenu de la box_infoSession du header.
// -> La box_infoSession peut afficher ( 'formulaire de connexion' ; Les infos clients ; 'formulaire d'inscription')

	session_start();
	
// Le controleur de session verifie s'il y a une demande de connexion;
// Définie le statut du $user (utilisateur : par defaut 'visiteur', répertorié 'client', administrateur 'admin')
// 				-> Création $client vide par defaut.
// Récupération du panier 'en cours'


	if(isset($_POST['connexion_admin'])){ require 'ctrl/admin/authentification.php';} // S'il y a une demande pour acces BACKOFFICE: il lance le traitement de la demande avec 'authentification.php';
	if(isset($_POST['Connexion'])){ require 'ctrl/client/authentification.php';} // S'il y a une demande de connexion client.
	if(!isset($show_boxAlert) && !isset($_GET['exec_boxAlert'])) {												// Si pas de conflit, pas besoin d'afficher la box alert.
					
				$show_boxAlert = FALSE;
	}
	elseif(isset($_GET['exec_boxAlert'])){
		
				$show_boxAlert = TRUE;
	}
	
	$session 	= (empty($_SESSION['statut']))		?	'visitor' 			:	$_SESSION['statut'];
	
	$user		= (!empty($_SESSION['user']))		?	$_SESSION['user']	:	new Client (	array(	'id_c'			=>	0,
																										'nom'			=>	'Visiteur',
																										'prenom'		=>	NULL,
																										'email'			=>	NULL,
																										'adresse'		=>	NULL,
																										'code_postal'	=>	NULL,
																										'ville'			=>	NULL,
																										'departement'	=>	NULL,
																										'telephone'		=>	NULL));
	
	
	switch($session){
		
		case 'visitor' 	:	$session_admin_open=false;$session_client_open=false;$session_visitor_open=true;
		break;
		case 'client'	:	$session_admin_open=false;$session_client_open=true;$session_visitor_open=false;
		break;
		case 'admin'	:	$session_admin_open=true;$session_client_open=false;$session_visitor_open= false;
		break;
		
	}
	
	//	Chargement des vues correspondantes à la session ouverte : 
	
	
	if($session_admin_open){		require 'views/headBand.php';}
	
	// gestion de la déconnexion
	
	if (isset($_GET['deconnexion'])){ 
	
			session_destroy();
			header('Location: index.php');
			exit();
		}