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
  public function get_product(int product_id) {
    $sql_query = "SELECT
     llx_product.rowid  AS id_p,
     llx_product.label AS titre,
     llx_product.price_min_ttc AS prix_achat,
     llx_product.price_ttc AS prix_vente,
     llx_product.tva_tx AS tva,
     llx_product_fournisseur_price.fk_soc AS id_producteur,
     llx_product.description AS description,
     llx_product.tosell AS is_active

     FROM llx_product, llx_product_fournisseur_price
     WHERE llx_product.rowid = :product_id
     AND  llx_product.rowid = llx_product_fournisseur_price.fk_product
     "
   $pro = $bdd.prepare($sql_query);
   if ($pro->rowCount() == 1){
      while ($donnees = $pro->fetch(PDO::FETCH_ASSOC))
        {
          $produit = new Produit ($donnees);
        }
      return $produit;
   } else { 
     return NULL;
   }
  }

}
?>
