<?php

// Le fichier traite le formulaire 'client'.
// Vérifie que les données sont correctes.
// Edit la base de donnée
// Renvoie vers la page admin avec un message $formAnswer

$action 	= (isset($_POST['Inscription']) AND !empty($_POST['action']))	?	$_POST['action']			:	NULL;

$id_c		= (isset($_POST['id_c']))			?	$_POST['id_c']					:	NULL;
$e			=	false;

$listAtt	= array('nom_c', 'prenom_c', 'email_c','mdp_c', 'adresse_c', 'cp_c', 'ville_c', 'telephone_c');

foreach($listAtt as $att){
	
	$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;		
	$e		= (!empty($$att))			?	FALSE						:	TRUE;
	
}

$dept = substr($cp_c, 0, 2);
switch($dept){
	case '77' : $departement_c = 'Seine-et-Marne';
	break;
	case '78' : $departement_c = 'Yvelines';
	break;
	case '91' : $departement_c = 'Essonne';
	break;
	case '92' : $departement_c = 'Hauts-de-Seine';
	break;
	case '93' : $departement_c = 'Seine-Saint-Denis';
	break;
	case '95' : $departement_c = "Val-d'Oise";
	break;
	default	  : $departement_c = NULL;
	break;
}

if(!$e){
	
	$client = new Client (	array(		'id_c'			=>	$id_c,
										'nom'			=>	$nom_c,
										'prenom'		=>	$prenom_c,
										'email'			=>	$email_c,
										'mdp'			=>	$mdp_c,
										'adresse'		=>	$adresse_c,
										'code_postal'	=>	$cp_c,
										'ville'			=>	$ville_c,
										'departement'	=>	$departement_c,
										'telephone'		=>	$telephone_c));
	
	
	switch($action){
		
		case	'ajouter'	:	add_client($client); $formAnswer =	'Nouveau client enregistré';
		break;
		case	'modifier'	:	set_client($client); $formAnswer =	'Client modifié';
		break;			
		
	}
	
}else{			// Si le formulaire comporte des erreurs (ici incomplet)
	
	$formAnswer =	'Formulaire incomplet';
	
}

if($session_admin_open){
	header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
}elseif($session_visitor_open){
	
	if(!$e){														// Si le formulaire n'a pas généré d'erreur
					
					$_SESSION['statut']	= 'client';										//  Changement du statut de la session.
					$_SESSION['user']	= get_clientByEmail($email_c);					//	Chargement du nouveau client en base de donnée.
					$panier->setId_c($_SESSION['user']->id_c());						// Déclaration de l'id_c du nouveau client.
					add_panier($panier);												//	Enregistrement direct du panier en bdd	
					$_SESSION['panier']		=	$user->get_panierEnCours();				// On charge le nouveau panier dans la session.
					
					header('Location: commander.php?step=Parametrage&formAnswer='.$formAnswer);exit(); // redirection vers parametrage.
			}
	
	header('Location: commander.php?step=Authentification&formAnswer='.$formAnswer);exit();					//	Redirection vers la page commande.
}