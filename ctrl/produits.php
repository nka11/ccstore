<?php

// CTRL_PRODUIT : récupère la liste des produits en fonction de la catégorie demandée. ( Catégorie définie par le CTRL_CATEGORIE);

	$listProduit = (!empty($reqCat)) ? getList_produit($reqCat) : getList_produit();
	
	$empty_listProduit = (!empty($listProduit)) ? false : true;