<?php

require_once("./model/ProduitDAO.php");
class ProduitDAOTest extends PHPUnit_Framework_TestCase
{

  public function testGetProduitList() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduits();
    foreach ($produits as $produit) {
      $this->assertInternalType('string',$produit->titre());
    }
  }
  public function testGetProduitsByCategory() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduitsByCategory(1);
    foreach ($produits as $produit) {
      $this->assertInternalType('string',$produit->titre());
      $pdao->getProduitCategories($produit);
    }
  }
}

