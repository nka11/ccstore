<?php

// 				PAGE Inscription

// Si l'utilisateur n'a jamais commandé, (pas client), on affiche le formulaire d'inscription

spl_autoload_register(function ($class) {
	include 'model/class/' . $class . '.class.php';
});

require "model/model.php";

// Ouverture et/ou récupération de session ouverte ( chargement du User / ouverte-fermeture de session)
require "ctrl/session.php";

// Chargement de la liste des catégories.
require "ctrl/categories.php"; 						// Le fichier appelle le model qui récupères la liste des catégories.

// Récupération et création du panier client
require 'ctrl/panier/ctrl_panier.php';

// Chargement du navigateur
require 'ctrl/leftNav/ctrl_leftNav.php';

$page = 'Inscription';

if(isset($_POST['Inscription'])) {
	require 'ctrl/post/editClient.php';
}
else {
	$action	=	'ajouter';
	require 'views/form/view_formInscription.php';
	$view_section = $view_formInscription;
}

//Chargement de la page
require 'views/gabarit/gabarit.php';