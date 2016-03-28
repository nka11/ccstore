<?php

// Box valid command est appelé par le controleur de panier.
// En fn de la valeur du panier : -> modification du nom de la balise contenant le bouton de validation.

	if( $panier->valeur() != 0){
	
		if($panier->valeur() > 0 && $panier->valeur() < 20){
		
			$divId	= 'notAvailable';
		
		}elseif($panier->valeur() >= 20){
		
			$divId = 'available';
		}
	
		require 'views/boxValidCommand/view_boxValidCommand.php';
	}