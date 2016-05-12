<?php

require_once("./model/ProductDAO.php");
class ProductDAOTest extends PHPUnit_Framework_TestCase
{

  public function testGetProducts() {
    $pdao = new ProductDAO();
    $products = $pdao->getProducts();
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->title());
    }
  }
  public function testGetProductsByCategory() {
    $pdao = new ProductDAO();
    $products = $pdao->getProductsByCategory(1);
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->title());
      $pdao->getProductCategories($product);
    }
  }
}

