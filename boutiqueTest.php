<?php 

/** Page Boutique.
 * Par defaut .......................: affiche @array $produits de tous les produits.
 * Si une categorie reçu.............: affiche @array $produits de la catégorie demandée.
 * Si un produit est reçu............: affiche détail du produit demandé.
 * Requière : @file ctrl/panier.php et @file ctrl/session.php
 * Autorise l'affichage du bouton "Commander maintenant".
 * Autorise l'affichage du panier.
 * Doit déclarer :	- $sstitrePage (par defaut vaut : "Tous les produits")
 *  				- $filename ( appel du fichier html)
 *					- $viewsection ( html du contenu de la page)
 * Appel la vue : 	- @file views/gabarits/gabarit.php.
 */

include "model/ProduitDAO.php";

/**
 * Déclarations :
 */
 
$pdao = new ProduitDAO();		// - du ProduitDAO
$page = "Boutique";

/**
 * Gestion de l'affichage
 */
 
if(isset($_GET['cat'])){
	// Declaration : sous titre page vaut : $cat
	$cat = (!empty($_GET['cat'])) ? htmlentities($_GET['cat'])	:	NULL;
	$sstitre = ucfirst($cat);
	// Vérifier l'existance de la catégorie
	// Si categorie existe : 
	$produits = $pdao->getProduitsByCategory($cat);
	$filename = "htmlproduits";
}
elseif(isset($_GET['id'])){
	// Declaration : sous titre page vaut : libellé(titre) du produit
	$id_p = (!empty($_GET['id'])) ? intval(htmlentities($_GET['id'])) : NULL;
	$produit = $pdao->getProduitById($id_p);
	$sstitre = ucfirst($produit->titre());
	$filename = "htmlproduit";
}
else{
	// Declaration : sous titre page vaut : Tous les produits
	$produits = $pdao->getProduits();
	$sstitre = "Tous les produits";
	$filename = "htmlproduits";
}

/**
 * views
 */
 
require "views/produit/". $filename .".php";
$viewsection = $$filename;

require "views/gabarit/gabaritTest.php";

/**
 * PASSAGE DE SESSION
 */