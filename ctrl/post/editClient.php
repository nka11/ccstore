<?php

// Le fichier traite le formulaire 'client'.
// Vérifie que les données sont correctes.
// Edit la base de donnée
// Renvoie vers la page admin avec un message $formAnswer

$action 	= (!empty($_POST['Enregistrer']))	?	$_POST['Enregistrer']			:	NULL;

$id_c		= (isset($_POST['id_c']))			?	$_POST['id_c']					:	NULL;
$e			=	false;

$listAtt	= array('nom_c', 'prenom_c', 'email_c', 'adresse_c', 'cp_c', 'ville_c', 'departement_c', 'telephone_c');

foreach($listAtt as $att){
	
	$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;		
	$e		= (!empty($$att))			?	FALSE						:	TRUE;
	
}

if(!$e){
	
	$client = new Client (	array(		'id_c'			=>	$id_c,
										'nom'			=>	$nom_c,
										'prenom'		=>	$prenom_c,
										'email'			=>	$email_c,
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
					add_panier($panier);												//	Enregistrement direct du panier en bdd	
					$_SESSION['panier']		=	$user->get_panierEnCours();				// On charge le nouveau panier dans la session.
			}
	
	header('Location: commander.php?step=client&formAnswer='.$formAnswer);exit();					//	Redirection vers la page commande.
}