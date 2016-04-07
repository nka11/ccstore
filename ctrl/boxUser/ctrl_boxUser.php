<?php

// ctrl_boxUser	: controle du contenu de la box User en fonction de la session.

if($session_client_open)	{
	
	$view_name		=	'view_boxClient';
	require 'views/client/view_boxClient.php';
}
else	{
	
	$view_name		=	'view_formConnexion';
	$form_action	=	'boutique.php';
	require 'views/form/view_formConnexion.php';
	
}
	
	$view_boxUser = $$view_name;