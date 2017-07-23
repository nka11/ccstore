<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class StoreController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
	//return parent::render('maintenance.html', array("message" => "Correction d'un bug entrainant l'affichage en doublon de certains produits."));
	$categories = $this->loadCategories();
	$products = $this->loadProducts();
    return parent::render('store.html', array(
		"products" => $products,
		"categories" => $categories
	));
  }
  /**
   * @Route("/:id")
   * @Method("GET")
   */
   function detailAction($id) {
	   $categories = $this->loadCategories();
	   $product = $this->pdao->getProductById($id);
	   if($product){
		   $product= $this->hydrateProduct($product);
		   $products= $this->pdao->getProductsBySupplier($product->fk_supplier());
			return parent::render('plug.html', array(
				"categories" => $categories,
				"product" => $product,
				"products"	=> $products
				));
	   } 
	   else return parent::render("error/403.html", array("message"=>"Le produit n'existe pas"));
   }
   /**
    * @Route("/category/:id")  																							// show products for one category
	*@Method("GET")
	*/
   function narrowAction($id){
	   $products=array();
	   $categories = $this->loadCategories();
	   // load asked category
	   $category = $this->cdao->getCategoryById($id);
	   if($category) $category = $this->hydrateCategory($category);
	   else{
		   $message="Catégorie demandée introuvable";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
	   }
	    return parent::render('store.html', array(
		"products" => $products,
		"categories" => $categories,
		"category"		=> $category
	));
   }
   /**
    * @Route("/add/basket")
	* @Method("POST")
	*/
   function addArticleAction(){
	   $amount=NULL;
	   $id_p=NULL;
	   if(array_key_exists('amount', $_REQUEST)
			&& array_key_exists('id_p', $_REQUEST)){
		   $amount= (int) $_REQUEST['amount'];
		   $id_p= (int) $_REQUEST['id_p'];
		   if($amount && $amount >0 && $amount!= NULL && $amount!=""
				&& $id_p && $id_p>0 && $id_p!="" ){
			  
			   try{
			   $product= $this->pdao->getProductById($id_p);
				}catch (Throwable $t) {
					$message = "Le produit sélectionné n'existe pas";
					http_response_code(403); //bad request
					return parent::render("error/403.html", array("message"=> $message));
				}
				if($product){
						$article= array(
							"amount"=>$amount,
							"product"=>$product,
							"value"=> number_format($product->price()*number_format($amount, 2), 2));
						$_SESSION['basket'][]= $article;
						$this->handle_session();
					
						$products= $this->loadProducts();
						$categories= $this->loadCategories();
					return parent::render('store.html', array(
						"products" => $products,
						"categories" => $categories
								));
				}
		   }else{
				$message="Quantité demandé non valide";
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
		   }
	   }else{
		   $message="Parametre(s) manquant(s)";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
	   }
   }
   /**
    * @Route("/del/basket/:id")
	* @Method("GET")
	*/
	function delArticleAction($id){
		foreach( $_SESSION['basket'] as $key=>$article){
			if($article['product']->id()== $id) $target_key= $key;
		}
		unset($_SESSION['basket'][$target_key]);
		return header('Location: '.'/store');
	}
   
}
