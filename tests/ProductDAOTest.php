<?php

require_once("./model/ProductDAO.php");
class ProductDAOTest extends PHPUnit_Framework_TestCase
{

  public function testGetProducts() {
    $pdao = new ProductDAO();
    $products = $pdao->getProducts();
	$this->assertNotInternalType('boolean', $products);
	$this->assertCount(1, $products);
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->title());
    }
  }
  public function testGetProductsByCategory() {
    $pdao = new ProductDAO();
    $products = $pdao->getProductsByCategory(1);
	$this->assertNotInternalType('boolean', $products);
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->title());
      $pdao->getProductCategories($product);
    }
  }
}

