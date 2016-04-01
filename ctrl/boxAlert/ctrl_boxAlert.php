<?php

// ctrl-boxAlert ----> Gère l'affichage du formulaire en cas de conflit de panier.
// Affiche la box Alert.

	if(isset($_POST['Selectionner'])){
		
		echo "J'ai bien reçu un choix utilisateur";
		$action	=	(!empty($_POST['ChoixUser']))	?	htmlentities($_POST['ChoixUser'])	:	NULL;
		
		switch($action)	{
			
			case	'fusionner'	:	
			break;
			case	'ecraser'	:	delete($user->get_panierEnCours());											// Suppression du panier en cours.
									add_panierOnly($_SESSION['panier']);										// Création du nouveau panier en base
									$newId_pa	= $_SESSION['user']->get_panierEnCours()->id_pa();				// Récupération de l'id du nouveau panier
									foreach( $_SESSION['panier']->list_lc() as $lc) {							// Enregistrement de chaque ligne de commande du paneir en session.
						
										$lc->setId_pa($newId_pa);
										$_SESSION['panier']->add_ligne_commande($lc);
									}
			break;
			case	'conserver'	:	$SESSION['panier']	=	$user->get_panierEnCours();
			break;

		}
				
		
		
	}
	else {
		
		require 'views/boxAlert/view_boxAlert.php';
		
	}