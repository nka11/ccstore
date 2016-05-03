<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProduitDAO.php';
class StoreController extends AbstractController {

  function indexAction() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduits();
    return parent::render('store.html', array("products" => $produits));
  }
}
