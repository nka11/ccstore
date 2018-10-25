<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class StoreController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
	$categories = $this->dbManager->loadCategories();
	$products = $this->dbManager->loadProducts();
    return parent::render('store.html', array(
		"products" => $products,
		"categories" => $categories
	));
  }
  /**
    * @Route("/")  																							// show products for one category
	* @Method("POST")
	*/
   function selectAction(){
	   $products=array();
	   $id_cat= ( array_key_exists('id_cat', $_POST))
				?	$_POST['id_cat']
				:	null;
		$category = ($id_cat && $id_cat!= null && $id_cat!="")
				?	$this->dbManager->loadCategory($id_cat)
				:	null;
	   $categories = $this->dbManager->loadCategories();
	   $products= ( !$category)
				?	$this->dbManager->loadProducts()
				:	array();
	    return parent::render('store.html', array(
		"products"	=>	$products,
		"categories" => $categories,
		"category"		=> $category
	));
   }
  /**
   * @Route("/:id")
   * @Method("GET")
   */
   function detailAction($id) {
		$productID= (int) $id;
		$categories = $this->dbManager->loadCategories();
		$product = $this->dbManager->loadProduct($productID);
		return parent::render('store/plug.html', array(
			"categories" => $categories,
			"product" => $product,
			));
   }
   /**
    * @Route("/category/:id")  																							// show products for one category
	*@Method("GET")
	*/
   function narrowAction($id){
	   $products=array();
	   $categories = $this->dbManager->loadCategories();
	   // load asked category
	   $category = $this->dbManager->loadCategory($id);
	    return parent::render('store.html', array(
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
				$product= $this->dbManager->loadProduct($id_p);
				if($product){
					$article= array(
							"amount"=>$amount,
							"product"=>$product,
							"value"=> number_format($product->price()*number_format($amount, 2), 2));
					$_SESSION['basket'][]= $article;
					$this->handle_session();
					$alert="Produit ajouté au panier";
				}
				else $alert= "Produit introuvable...";
		    }
		    else $alert="Quantité demandé non valide";
	    }
		else $alert="Parametre(s) manquant(s)";
		$categories = $this->dbManager->loadCategories();
		$products = $this->dbManager->loadProducts();
		return parent::render("store.html", array(
									"alert"=>$alert,
									"products"=>$products,
									"categories"=>$categories
									));
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
