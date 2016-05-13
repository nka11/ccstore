<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProductDAO.php';
require_once './model/CategoryDAO.php';
class StoreController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $pdao = new ProductDAO();
	$cdao = new CategoryDAO();
    $products = $pdao->getProducts();
	$categories = $cdao->getCategories();
    return parent::render('store.html', array(
		"products" => $products,
		"categories" => $categories
	));
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
