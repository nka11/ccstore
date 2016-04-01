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
			
			$temp_panier	=	$_SESSION['user']->get_panierEnCours();
			
			if(!empty($temp_panier)){												// Si un panier existe en base
				
				if(empty($_SESSION['panier']->list_lc())){							// et que la liste d'articles en session est vide.
					
					$_SESSION['panier'] = $temp_panier;								// Le panier en base est chargé dans la session
				}
				else {
					
					$show_boxAlert = TRUE;
					//require 'ctrl/boxAlert/ctrl_boxAlert.php';						// S'il y a un panier en session et un panier en base --> ctrl conflit
				}	
			}
			else {																	// Si pas de panier en base
				$_SESSION['panier']->setId_c($_SESSION['user']->id_c());			// Déclaration de l'id_c (client)
				
				if(!empty($_SESSION['panier']->list_lc())) {						// et qu'il existe un panier en session
					add_panierOnly($_SESSION['panier']);								// Enregistrement du panier en BDD (et uniquement du panier,cad, pas les lignes de commandes)
					$newId_pa	= $_SESSION['user']->get_panierEnCours()->id_pa();		// Récupération de l'id du nouveau panier.
					$_SESSION['panier']->setId_pa($newId_pa);							// Affectation de l'id au panier en session.
					
					
					foreach( $_SESSION['panier']->list_lc() as $lc){					// Enregistrement de chaque ligne de commande du paneir en session.
						
						$lc->setId_pa($newId_pa);
						$_SESSION['panier']->add_ligne_commande($lc);
						
					}
				}
				
			}
				
	}}