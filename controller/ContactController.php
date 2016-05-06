<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProduitDAO.php';
class ContactController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduits();
    return parent::render('contact.html', array("products" => $produits));
  }
}