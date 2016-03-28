<?php

// CTRL_CATEGORIE : - Récupération de la liste des catégories.
	
	$reqCat		=	(isset($_GET['categorie'])) ? get_categorie(htmlentities($_GET['categorie'])) : NULL;
	$listCat 	=  getList_categorie();// Si une categorie est sélectionnée par l'utilisateur, on fournit la catégorie à la fonction get_list.
	$slistCat	= (!empty($reqCat)) ? getList_categorie($reqCat) : NULL;
	$empty_listCat = (empty($listCat)) ? true : false; // Pour échapper les erreurs d'affichages si la liste est vide : Deux vues possibles.

	require "views/categorie/view_listCat.php";		// Appelle la vue du listing catégories.