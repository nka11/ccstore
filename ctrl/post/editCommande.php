<?php

// edit-COMMAND
// Traitement des données reçu par le formulaire view_formCommande
	
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
		
		$commande	=	new Commande (array(		'id_com'			=>	$id_com,
													'id_pa'				=>	$panier->id_pa(),
													'id_c'				=>	$user->id_c(),
													'mode_liv'			=>	$mode_liv,
													'mode_paimement'	=>	$mode_paiement,
													'commentaire'		=>	$commentaire,
													'statut'			=>	'En attente de validation'));
													
		if($validation_requise) {
			
			$_SESSION['panier']	=	$commande;
			
		}
		else {
			
			switch ($action){
				
				case	'ajouter'	:	add_commande($commande); $formAnswer =  ' Votre commande a bien été enregistrée';
				break;
				case	'modifier'	:	set_commande($commande); $formAnswer = 'Votre commande a bien été modifiée';
				break;
	
			}
			
		}
		
		
		
	}