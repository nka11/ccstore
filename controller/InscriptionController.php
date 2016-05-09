<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProductDAO.php';
class InscriptionController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $pdao = new ProductDAO();
    $products = $pdao->getProducts();
    return parent::render('inscription.html', array("products" => $products));
  }
}