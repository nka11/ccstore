<?php

require("./model/CategorieDAO.php");
class TestCatDAO extends PHPUnit_Framework_TestCase
{
    // ...

    public function testGetCatList()
    {
      $cdao = new CategorieDAO();
      $categories = $cdao->get_list_categories();
      foreach ($categories as $categorie) {
        echo "_".$categorie->tag()."\n";
      }
    }

    // ...
}

