<?php
require('./class/Produit.class.php');
class CategorieDAO {
  public function __construct() {
  // initialize here dolibarr bdd
  }
  /**
   * Get a category by ID
   */
  public function get_categorie(int $categorie_id) {
    $sql_query = "SELECT
     llx_categorie.rowid  AS id_cat,
     llx_categorie.fk_parent AS id_parent,
     llx_categorie.label AS tag,

     FROM llx_categorie
     WHERE llx_categorie.rowid = :categorie_id
    ";
    $query = $this->bdd->prepare($sql_query);
    $query->bindValue(':categorie_id', $product_id, PDO::PARAM_INT);
    $query->execute();
   
   if ($query->rowCount() == 1){
      while ($donnees = $query->fetch(PDO::FETCH_ASSOC))
        {
          $categorie = new Categorie ($donnees);
        }
      return $categorie;
   } else { 
     return NULL;
   }
  }
  public function get_list_categories(int $parent=0) {
    $categories = array();
    $sql_query = "SELECT
     llx_categorie.rowid  AS id_cat,
     llx_categorie.fk_parent AS id_parent,
     llx_categorie.label AS tag,

     FROM llx_categorie
     WHERE
      llx_categorie.fk_parent = :parent
    ";
    $query = $this->bdd->prepare($sql_query);
    $query->bindValue(':parent', $parent, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
      while ($cat_data = $query->fetch()) {
        array_push($categories,new Categorie($data));
      }
    }
    return $categories;
  }
}
?>
