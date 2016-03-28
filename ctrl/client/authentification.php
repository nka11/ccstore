<?php
// Reception des données du formulaire de connexion administrateur.
// Confert le statut 'client' à la session.
// Charge dans la session le panier en cours du client si existant.

	$login 	= (!empty($_POST['login']))	?	htmlentities($_POST['login'])	:	NULL;
	$pw		= (!empty($_POST['mdp']))	?	htmlentities($_POST['mdp'])		:	NULL;
	$list_var_form = array('login', 'pw');
	$e		= 0;
// Traitement des données

	// Verification 'formulaire incomplet?' : 
	
	foreach( $list_var_form as $nomVar){	$e += (!empty($$nomVar)) ? 0 : 1;}
	
	// Déclaration de la réponse du formulaire
	if		($e != 0)					{	$formAnswer = 'Formulaire incomplet';}
	elseif	(empty(get_clientByEmail($login)))	{	$formAnswer = "L'administrateur demandé n'existe pas";}
	// Authentification de l'utilisateur
	else	{								$formAnswer = ( get_clientByEmail($login)->mdp() != $pw) ? 'Mot de passe incorrect' : NULL;
		
		if(empty($formAnswer)){ unset($formAnswer);	// Destruction de $formAnswer si pas de réponse.
			
			$_SESSION['statut'] = 'client';					// Ouverture de session client;
			$_SESSION['user']	= get_clientByEmail($login);		// Création de $_SESSION['user'];
			$_SESSION['panier']	=	$_SESSION['user']->get_panierEnCours();
	}}