<?php
require('./class/Produit.class.php');
class ProductDAO {
  public function __construct() {
  // initialize here dolibarr bdd
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
    $query = $this->bdd.prepare($sql_query);
    $query.bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $query.execute();
   
   if ($query->rowCount() == 1){
      while ($donnees = $query->fetch(PDO::FETCH_ASSOC))
        {
          $produit = new Produit ($donnees);
        }
      return $produit;
   } else { 
     return NULL;
   }
  }
  public function get_list_product($categorie=NULL) {
    
    $sql_query = "SELECT
      llx_product.rowid  AS id_p,
     llx_product.label AS titre,
     llx_product.price_min_ttc AS prix_achat,
     llx_product.price_ttc AS prix_vente,
     llx_product.tva_tx AS tva,
     llx_product_fournisseur_price.fk_soc AS id_producteur,
     llx_product.description AS description,
     llx_product.tosell AS is_active

     FROM llx_product, llx_product_fournisseur_price, llx_categorie_product
     WHERE 
        llx_product.rowid = llx_product_fournisseur_price.fk_product
     AND
        llx_product.rowid = llx_categorie_product.fk_product
    ";
  }
}
?>
