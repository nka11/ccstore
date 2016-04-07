<?php

// Box valid command est appelé par le controleur de panier.
// En fn de la valeur du panier : -> modification du nom de la balise contenant le bouton de validation.
// Définition de la valeur de "$step" -> Saute l'étape d'authentification du client.

	if( $panier->valeur() != 0){
	
		if($panier->valeur() > 0 && $panier->valeur() < 20){
		
			$divId	= 'notAvailable';
		
		}elseif($panier->valeur() >= 20){
		
			$divId = 'available';
		}
	
		$step	=	($session_client_open)	?	'Parametrage'	:	'Authentification';
	
		require 'views/boxValidCommand/view_boxValidCommand.php';
	}