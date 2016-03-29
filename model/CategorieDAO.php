<?php
require('./model/class/Categorie.class.php');
class CategorieDAO {
  public $bdd;
  public function __construct(
    string $dbstring='mysql:host=localhost;dbname=dolib_test',
    string $dbuser='testuser',
    string $dbpass='testpass' ) {
    // initialize here dolibarr bdd
    $this->bdd = new PDO($dbstring,$dbuser,$dbpass);
    $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $this->bdd->exec("SET CHARACTER SET utf8");
  }
  /**
   * Get a category by ID
   */
  public function getCategorie(int $categorie_id) {
    $sql_query = "SELECT
     categorie.rowid  AS id_cat,
     categorie.fk_parent AS id_parent,
     categorie.label AS tag
     FROM llx_categorie AS categorie
     WHERE categorie.rowid = :categorie_id
    ";
    $query = $this->bdd->prepare($sql_query);
    $query->bindValue(':categorie_id', $categorie_id, PDO::PARAM_INT);
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
  public function getListCategories(int $parent=0) {
    $categories = array();
    $sql_query = "SELECT
     categorie.rowid  AS id_cat,
     categorie.fk_parent AS id_parent,
     categorie.label AS tag
     FROM llx_categorie AS categorie
     WHERE
      fk_parent = :parent
    ";
    $query = $this->bdd->prepare($sql_query);
    $query->bindValue(':parent', $parent, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
      while ($cat_data = $query->fetch()) {
        array_push($categories,new Categorie($cat_data));
      }
    }
    return $categories;
  }
}
?>
