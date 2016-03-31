<?php

// ctrl-boxAlert ----> Gère l'affichage du formulaire en cas de conflit de panier.
// Affiche la box Alert.

	if(isset($_POST['Selectionner'])){
		
		$action	=	(!empty($_POST['ChoixUser']))	?	htmlentities($_POST['ChoixUser'])	:	NULL;
		
		switch($action)	{
			
			case	'fusionner'	:	
			break;
			case	'ecraser'	:	delete($user->get_panierEnCours());
			break;
			case	'conserver'	:	$SESSION['panier']	=	$user->get_panierEnCours();
			break;

		}
				
		
		
	}
	else {
		
		require 'views/boxAlert/view_boxAlert.php';
		
	}