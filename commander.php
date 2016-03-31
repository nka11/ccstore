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
$is_commande_validated		= (isset($_POST['Confirmer']))											?	true	:	false;			// Une nouvelle commande à été validée.

$formAnswer = (isset($_GET['formAnswer'])) ? htmlentities($_GET['formAnswer']) : NULL;				//Si un formulaire à généré une erreur.


// TRAITEMENT DES DONNEES ET APPEL DES VUES

if($step=='Authentification') {
	
	$page				= 'Authentification';
	$step				= ($session_client_open)	?	'Parametrage'	:	'Authentification';							// --> Declaration de l'étape à la valeur 'Parametrage'
	
	if(!$is_form_completed && !$session_client_open) {					
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
		$validation_required	=	TRUE;
		require 'ctrl/post/editCommande.php';
		require 'views/commande/view_recapCommande.php';																		// Gestion de la commande : -> Enregistrement en bdd.
	}
		
}




elseif ($step == 'paiement') {
	$page	=	'Paiement';
	if ($is_commande_validated) {
		
		add_commande($panier);						// Si la commande est validée on l'ajoute à la base de donnée.
		if ( $panier->mode_paiement() == 'En ligne') { 
		/* REDIRECTION VERS PAYBOX/ paypal*/
		} else {
			require 'view_confirmationCommande.php';
		}
	} elseif (!$is_commande_validated){
		
		require 'views/commande/view_recapCommande.php';
	}
} elseif ($step == 'annuler') {
	header('Location: boutique.php?$formAnswer=Commande annulée&show=list');
	exit();
}

//Chargement de la page
require 'views/gabarit/gabarit.php';

//PASSAGE DE SESSION

$_SESSION['panier'] = (!empty($panier))	?	$panier	:	NULL;
		?>