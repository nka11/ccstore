<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class InscriptionController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $products = $this->dbManager->loadProducts();
    return parent::render('inscription.html', array("products" => $products));
  }
}