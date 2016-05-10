<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProductDAO.php';
class StoreController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $pdao = new ProductDAO();
    $products = $pdao->getProducts();
    return parent::render('store.html', array("products" => $products));
  }
  /**
   * @Route("/:id")
   * @Method("GET")
   */
   function itemAction($id) {
	   $pdao = new ProductDAO();
	   $product = $pdao->getProductById($id);
	   return parent::render('plug.html', array("product" => $product));
   }
}
