<?php

require("./model/ProductDAO.php");
class ProductDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testGetProducts() {
      $pdao = new ProductDAO();
      $pdao->initBdd();
      $products = $pdao->getListProduct();
      foreach ($products as $product) {
        $this->assertInternalType('string',$product->titre());
        $this->assertInternalType('int',$product->id_p());
      }
    }
    // ...
}

