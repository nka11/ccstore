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

$step						= (isset($_GET['step']))												?	htmlentities($_GET['step'])	:	NULL;
$is_form_completed			= (isset($_POST['Enregistrer']) || isset($_POST['Inscription']))		?	true	:	false;			// Un nouveau client à rempli le formulaire.
$form_action				= 'commander.php?step='.$step;
$is_command_validated		= (isset($_GET['Confirmer']))											?	true	:	false;			// Une nouvelle commande à été validée.

$formAnswer = (isset($_GET['formAnswer'])) ? htmlentities($_GET['formAnswer']) : NULL;				//Si un formulaire à généré une erreur.

// TRAITEMENT DES DONNEES ET APPEL DES VUES

if($step=='Authentification') {
	
	$page				= 'Authentification';
	$step				= ($session_client_open)	?	'Parametrage'	:	'Authentification';							// --> Declaration de l'étape à la valeur 'Parametrage'
	
	if(!$is_form_completed && !$session_client_open) {	
		$action = 'ajouter';																							// Declaration de l'action.
		require 'views/form/view_formInscription.php';																	// Chargement vue du formulaire "nouveau client" 
		require 'views/form/view_formConnexion.php';																	// et du formulaire d'authentification
		require 'views/form/view_formCommandeAuthentification.php';
		$view_section = $view_formCommandeAuthentification;	
	}
	else {
		require 'ctrl/post/editClient.php';																				// Traitement du formulaire "nouveau client"
	}
}

if($session_client_open && $step == 'Parametrage') {
	
	$page			=	'Parametrage';
	
	if (!$is_form_completed) {																							// Si pas de renseignement sur method livraison et paiement
		require 'views/form/view_formCommandeParametrage.php';															// Chargement de l'affichage du formulaire	parametrage commande.		
	}
	else {
		require 'ctrl/post/editCommande.php';
		$page = 'Validation de la commande';
		require 'views/commande/view_recapCommande.php';																		// Gestion de la commande : -> Enregistrement en bdd.
		$view_section = $view_recapCommande;
	}
}


if( $step == 'Validation' && $is_command_validated) {								// Etape de validation
	
	require 'ctrl/post/editCommande.php';
	$step = ($_SESSION['panier']->mode_paiement() == 'En ligne')	?	'Paiement'	:	'Confirmation';										// Redirection vers etape de paiement si necessaire.
}

if ($step == 'Paiement') {
	$page	=	'Paiement';
	/* REDIRECTION VERS PAYBOX/ paypal*/		
}

if( $step == 'Confirmation'){
	header('Location: boutique.php?formAnswer='.$formAnswer.'&show=list');
	exit();
}

if ($step == 'Annuler') {
	header('Location: boutique.php?formAnswer=Commande annulée&what=produit&show=list');
	exit();
}

//Chargement de la page
require 'views/gabarit/gabarit.php';

//PASSAGE DE SESSION
if(isset($commande)){
	
	$_SESSION['panier'] = (!empty($commande))	?	$commande	:	NULL;	
}
else {
	$_SESSION['panier'] = (!empty($panier))	?	$panier	:	NULL;
}
		?>