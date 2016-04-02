<?php

// edit-COMMAND
// Traitement des données reçu par le formulaire view_formCommande
// Deux cas : 1- la commande est en construction -> création de la commande et passage en session
//			  2- La commande est confirmée -> Effacer le panier correspondant en bdd si commande->id_pa()!=0, 	et sauvegarde de la commande en bdd.


if(!$is_command_validated) {
	
	$action			=	(isset($_POST['action']))			?	htmlentities($_POST['action'])			:	NULL;

	$mode_liv		=	(!empty($_POST['mode_liv']))		?	htmlentities($_POST['mode_liv'])		:	NULL;
	$mode_paiement	=	(!empty($_POST['mode_paiement']))	?	htmlentities($_POST['mode_paiement'])	:	NULL;
	$commentaire	=	(!empty($_POST['commentaire']))		?	htmlentities($_POST['commentaire'])		:	NULL;

	$id_com			=	(isset($_POST['id_com']))			?	intval($_POST['id_com'])				:	NULL;


	$e			=	false;

	$listAtt	= array('mode_liv', 'mode_paiement');

	foreach($listAtt as $att){
		
		$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;		
		$e		= (!empty($$att))			?	FALSE						:	TRUE;
		
	}

	if(!$e){
		
		
		if($panier->id_pa()==0) {
			
			add_panier($panier);			// On ajoute un nouveau panier en BDD pour obtenir un  nouvel id_pa().
				
			$panier = $user->get_lastPanierCree();	// Puis on récupère le dernier panier créé en base de donnée pour cette utilisateur. Permet de renseigner l'id_pa correcte.
			
		}

		
		$commande	=	new Commande (array(		'id_com'			=>	$id_com,
													'id_pa'				=>	$panier->id_pa(),
													'id_c'				=>	$user->id_c(),
													'mode_liv'			=>	$mode_liv,
													'mode_paiement'		=>	$mode_paiement,
													'commentaire'		=>	$commentaire,
													'statut'			=>	'En attente de validation'));
										
		
		$_SESSION['panier']	=	$commande;
			
	}
	
}
else {
	
	$action	=	(isset($_GET['action']))	?	htmlentities($_GET['action'])	:	NULL;
	
	switch ($action){
		
		case	'ajouter'	:	add_commande($_SESSION['panier']);
								$formAnswer =  ' Votre commande a bien été enregistrée';
		break;
		case	'modifier'	:	set_commande($_SESSION['panier']); $formAnswer = 'Votre commande a bien été modifiée';
		break;

	}

	if($action = 'ajouter') { 		// Si la commande est enregistrée pour la première fois en base.
	
		$panier->clearPanier();			// Suppression du panier correspondant. ( Concervation des lignes de commande).
	
	}
	
}