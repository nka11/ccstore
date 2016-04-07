<?php

spl_autoload_register(function ($class) {
	include 'model/class/' . $class . '.class.php';
});

require "model/model.php";
$form_action = 'index.php';

// Ouverture et/ou récupération de session ouverte
require "ctrl/session.php";

// Chargement de la liste des catégories.
require "ctrl/categories.php"; // Le fichier appelle le model qui récupères la liste des catégories.

// Récupération et création du panier client
require 'ctrl/panier/ctrl_panier.php';

// Chargement du header
require 'ctrl/header/ctrl_header.php';


// Chargement du footer
require 'views/footer/view_footer.php';		


//Chargement de la page
require 'views/gabarit/gabaritIndex.php';

//PASSAGE DE SESSION