<?php

require("./model/CategorieDAO.php");
class CategorieDAOTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testGetCatList() {
      $cdao = new CategorieDAO();
      $categories = $cdao->getListCategories();
      foreach ($categories as $categorie) {
        $this->assertInternalType('string',$categorie->tag());
        $this->assertInternalType('int',$categorie->id_cat());
        $this->assertInternalType('int',$categorie->id_parent());
      }
    }
    public function testGetCategory() {
      $cdao = new CategorieDAO();
      $category = $cdao->getCategorie(1);
    }

    // ...
}

