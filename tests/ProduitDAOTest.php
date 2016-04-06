<?php

require("./model/ProduitDAO.php");
class ProduitDAOTest extends PHPUnit_Framework_TestCase
{

  public function testGetProduitList() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduits();
    echo "\nListe Produits";
    foreach ($produits as $produit) {
      echo "\n\t".$produit->titre();
    }
  }
  public function testGetProduitsByCategory() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduitsByCategory(1);
    echo "\nListe Produits categorie 1";
    foreach ($produits as $produit) {
      echo "\n\t".$produit->titre();
      $pdao->getProduitCategories($produit);
//      echo "\n".json_encode($produit->categories(), JSON_PRETTY_PRINT);
    }
  }
}

