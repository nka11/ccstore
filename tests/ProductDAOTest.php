<?php

require("./model/ProductDAO.php");
class ProductDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

  public function testGetProduct() {
    $pdao = new ProductDAO();
    $pdao->initBdd();
    $product = $pdao->getProduct(1);
    $this->assertEquals(3,count($product->categories()));

  }
  public function testGetProducts() {
    $pdao = new ProductDAO();
    $pdao->initBdd();
    $products = $pdao->getListProduct();
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->titre());
      $this->assertInternalType('int',$product->id_p());
    }
  }
  public function testGetProductsByCategorie() {
    $pdao = new ProductDAO();
    $pdao->initBdd();
    $products = $pdao->getListProduct(2);
    $this->assertEquals(8,count($products));
    foreach ($products as $product) {
      $this->assertInternalType('string',$product->titre());
      $this->assertInternalType('int',$product->id_p());
    }

  }
    // ...
}

