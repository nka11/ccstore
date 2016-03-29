<?php

require("./model/CategorieDAO.php");
class CategorieDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testGetCatList() {
      $cdao = new CategorieDAO();
      $cdao->initBdd();
      $categories = $cdao->getListCategories();
      foreach ($categories as $categorie) {
        $this->assertInternalType('string',$categorie->tag());
        $this->assertInternalType('int',$categorie->id_cat());
        $this->assertInternalType('int',$categorie->id_parent());
      }
    }
    public function testGetCategory() {
      $cdao = new CategorieDAO();
      $cdao->initBdd();
      $categorie = $cdao->getCategorie(1);
      $this->assertInternalType('string',$categorie->tag());
      $this->assertInternalType('int',$categorie->id_cat());
      $this->assertInternalType('int',$categorie->id_parent());
    }

    public function testGetCategoriesProduct() {
      $cdao = new CategorieDAO();
      $cdao->initBdd();
      $categories = $cdao->getCategoriesProduct(1);
      $this->assertEquals(3, count($categories));
      foreach ($categories as $categorie) {
        $this->assertInternalType('string',$categorie->tag());
        $this->assertInternalType('int',$categorie->id_cat());
        $this->assertInternalType('int',$categorie->id_parent());
      }
    }


    // ...
}

