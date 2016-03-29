<?php
require_once('./model/AbstractDAO.php');
require_once('./model/CategorieDAO.php');
require_once('./model/class/Product.class.php');
class ProductDAO extends AbstractDAO {
  private $catdao;

  public function __construct() {
    $this->catdao = new CategorieDAO();
  }

  public function initBdd(
    string $dbstring='mysql:host=localhost;dbname=dolib_test',
    string $dbuser='testuser',
    string $dbpass='testpass' 
  ) {
    parent::initBdd($dbstring,$dbuser,$dbpass);
    $this->catdao->setBdd($this->bdd);
  }
  public function setBdd(PDO $bdd) {
    parent::setBdd($bdd);
    $this->catdao->setBdd($this->bdd);
  }

  /**
   * Get a product by ID
   * missing : 
   *   tag_cat
   *     from llx_categorie_product / llx_categorie as list of tags (change model)
   *   is_active (on devrait trouver dans la base dolibarr)
   *   img (certainement a créer/browser les pj des obj dolibarr)
   */
  public function get_product(int $product_id) {
    $produit = NULL;
    $sql_query = "SELECT
     llx_product.rowid  AS id_p,
     llx_product.label AS titre,
     llx_product.price_min_ttc AS prix_achat,
     llx_product.price_ttc AS prix_vente,
     llx_product.tva_tx AS tva,
     llx_product.description AS description,
     llx_product.tosell AS is_active

     FROM llx_product
     WHERE llx_product.rowid = :product_id
    ";
    $query = $this->bdd->prepare($sql_query);
    $query->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $query->execute();
   
   if ($query->rowCount() == 1){
      while ($donnees = $query->fetch(PDO::FETCH_ASSOC))
        {
          $produit = new Produit ($donnees);
        }
   }
   return $produit;
  }
  public function getListProduct($categorie=NULL) {
    $products = array();
    $sql_query = "SELECT
      llx_product.rowid  AS id_p,
     llx_product.label AS titre,
     llx_product.price_min_ttc AS prix_achat,
     llx_product.price_ttc AS prix_vente,
     llx_product.tva_tx AS tva,
     llx_product.description AS description,
     llx_product.tosell AS is_active

     FROM llx_product, llx_categorie_product
    ";
    if ($categorie != NULL) {
      $sql_query .= "WHERE llx_categorie_product.fk_categorie = :categorie
       AND llx_categorie_product.fk_product = llx_product.rowid"; 
    }
    $query = $this->bdd->prepare($sql_query);
    if ($categorie != NULL) {
      $query->bindValue(':categorie',$categorie, PDO::PARAM_INT);
    }
    $query->execute();
    if ($query->rowCount() > 1) {
      while ($product_data = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($products,new Product($product_data));
      }
    }
    return $products;
  }
}
?>
