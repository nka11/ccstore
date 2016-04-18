<?php 
/**
 *	Page Inscription
 *Gere l'inscription Client en bdd.
 *Par defaut : Affiche le formulaire d'incription.
 *Recoit les données du formulaires.
 *Doit déclarer :	- $viewsection ( html du contenu de la page).
 *Appel la vue : 	- @file views/gabarits/gabarit.php.
 */
 
include "model/ClientDAO.php";

/**
 * Déclarations :
 */

$clientdao = new ClientDAO();
$page = 'Inscription';
$formaction = 'ajouter';

/**
 * Gestion de l'affichage
 */

if(isset($_POST['inscription'])) {	// Si le formulaire a été rempli.
	include 'ctrl/post/editclientTest.php';		// @file editclient.php retourne : array $error / string alert
	if(count($errors)==0){
		header("Location:boutiqueTest.php?alert=$alert");
		exit();
	}
}

/**
 * Views
 */
 
require 'views/form/htmlforminscription.php';
$viewsection = $htmlforminscription;
$linkshead[] = 'cssforminscription.css';
require "views/gabarit/gabaritTest.php";

/**
 * PASSAGE DE SESSION
 */