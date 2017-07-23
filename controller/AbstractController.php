<?php

require_once './vendor/autoload.php';
require_once './model/class/Customer.class.php';
require_once './model/ProductDAO.php';
require_once './model/CategoryDAO.php';
require_once './model/SupplierDAO.php';
require_once './model/OrderDAO.php';
require_once './model/OrderLineDAO.php';
require_once './model/CustomerDAO.php';
use Pux\Mux;
class AbstractController extends \Pux\Controller {
  private $loader;
  private $twig;
  protected	$custdao,
						$pdao,
						$sdao,
						$odao,
						$oldao,
						$adao,
						$cdao;
  public $session;
  public $orderblock;
  public $base_path;
  
   public function __construct() {
    $this->loader = new Twig_Loader_Filesystem('./templates');

    $this->twig = new Twig_Environment($this->loader, array(
			'debug'=>true
//        'cache' => './template_cache',
      ));
	  //$twig->getExtension('Twig_Extension_Core')->setNumberFormat(2, '.', ',');
	  //$this->twig->addExtension(new Twig_Extension_Debug());
    $this->handle_session();
	$this->loadDAO();
	$this->setOrderblock();
    $this->base_path = substr($_SERVER['REQUEST_URI'],
      0,
      strripos($_SERVER['REQUEST_URI'],$_REQUEST['path']));
	
   }
   
    public function render($template, $data=[]) {
		$data['session'] = $this->session;
		$data['path'] = $_REQUEST['path'];
		$data['base_path'] = $this->base_path;
		$data['orderblock'] = $this->orderblock;
		return $this->twig->render($template,$data);
  }
  
  public function handle_session() {
	  if(empty($_SESSION)){
		session_start();
	  }
      $this->session['status'] = (empty($_SESSION['status']))
      ? 'visitor'
      : $_SESSION['status'];
      $this->session['user'] = (!empty($_SESSION['user']))
      ? $_SESSION['user']
      : null;
	$this->session['basket'] = (!empty($_SESSION['basket']))
	 ? $_SESSION['basket']
	 : array();
	 $this->session['basket_amount']=NULL;
	 foreach($this->session['basket'] as $line){
		$this->session['basket_amount']+= number_format($line['value'],2);
	}
	$_SESSION = $this->session;
  }
  
  public function loadDAO(){
	  global  $db_string, $db_user, $db_password;
	  $db_connect = new PDO($db_string, $db_user, $db_password);
	  $this->custdao= new CustomerDAO($db_connect);
	  $this->pdao= new ProductDAO($db_connect);
	  $this->cdao= new CategoryDAO($db_connect);
	  $this->sdao= new SupplierDAO($db_connect);
	  $this->odao= new OrderDAO($db_connect);
	  $this->oldao= new OrderLineDAO($db_connect);
	  $this->adao= new AdminDAO($db_connect);
  }
  
  public function loadProducts(){
	  //$pdao= new ProductDAO();
	  $products= $this->pdao->getProducts();
	  foreach($products as $p){
		  $p= $this->hydrateProduct($p);
	  }
	  return $products;
  }
  
  public function setOrderblock(){
		$closing= new DateTime(date("Y-m-d"));
		$opening= new DateTime(date("Y-m-d"));
		$closing_interval= ( date("N") < 4 )
										? "+". (3 - date("N")) . " day"
										:  "+". (10 - date("N")) . " day";
		$opening_interval= ( date("N") < 4 )
										?	"-". (3 + date("N")) . " day"
										:	"-". (date("N") - 4) . " day";
		$week_delivery= ( date("N") < 4 )
										? date("W")
										:  date("W") + 1;
		$closing->modify($closing_interval);
		$opening->modify($opening_interval);
		$this->orderblock= array(
								"week"		=>	$week_delivery,
								"opening"	=>	$opening->format("Y-m-d"),
								"closing"	=>	$closing->format("Y-m-d")
								);
  }
  
  public function loadCategories(){
	  $categories= $this->cdao->getCategories(); //get all categories
	  foreach($categories as $category){              // for each :
		$badge=0;
		$childCats = $this->cdao->getCategoriesByParent($category->id());	// check this category is parent ?
		  
		  if(!$childCats)	$badge= count($this->pdao->getProductsByCategory($category->id()));  // if not, count product for this category
		  else{																										//if this cat is parent,  fetch all undercategories
			  foreach($childCats as $cat){																		// for each
				  $badge += count($this->pdao->getProductsByCategory($cat->id()));			// sum number of product in badge
					$cat->setBadge(count($this->pdao->getProductsByCategory($cat->id())));		//hydrate this underCat.
			  }
		  }
		  $category->setChildCats($childCats); 		// set undercategories list
		  $category->setBadge($badge);				// hydrate this cat with badge.
	  }
	return $categories;
  }
  
  public function loadSuppliers(){
	  $suppliers= $this->sdao->getSuppliers();
	  foreach($suppliers as $supplier){
		  $supplier = $this->hydrateSupplier($supplier);
	  }
	  return $suppliers;
  }
  public function loadCustomers(){
	$customers= $this->custdao->getCustomers();
	return $customers;
  }
  public function loadOrders(){
	  $orders= $this->odao->getOrders();
	  foreach($orders as $order){
		  $order= $this->hydrateOrder($order);
	  }
	  return $orders;
  }
  public function hydrateCategory(Category $category){
	   $parentBadge = 0;
	   $childCats = $this->cdao->getCategoriesByParent($category->id());
	   if($childCats){
		   // if true -> load products for all childCats
		   foreach ($childCats as $cat){
			   $childBadge= 0;
			   $products= $this->pdao->getProductsByCategory($cat->id());
			   if($products){
				   foreach($products as $product){
					   $product = $this->hydrateProduct($product);
				   }
			   }
			   $childBadge= count($products);
			   $cat->setBadge($childBadge);
			   $cat->setProducts($products);
			   $parentBadge += $childBadge;
		   }
		   $category->setChildCats($childCats); // and hydrate this category with childCats
		   $category->setBadge($parentBadge); // and hydrate this category with parentBadge
		   foreach($category->childCats() as $childCat){
				foreach($childCat->products() as $product){
					$allproducts[] = $product;
				}
			}
			$category->setProducts($allproducts);
	   }
	   else{	// if false -> load product for this category
		   $products = $this->pdao->getProductsByCategory($category->id());
		   if($products){
				   foreach($products as $product){
					   $product = $this->hydrateProduct($product);
				   }
		   }
		   $category->setProducts($products);
	   }
	   return $category;
  }
  public function hydrateProduct(Product $product){
	  //$this->sdao= new SupplierDAO();
	  $supplier= $this->sdao->getSupplierById($product->fk_supplier());
	  $product->setSupplier($supplier);
	  return $product;
  }
  
  public function hydrateSupplier(Supplier $supplier){
	  $products= $this->pdao->getProductsBySupplier($supplier->id());
	  $supplier->setProducts($products);
	  return $supplier;
  }
  
  public function hydrateOrder(Order $order){
	  $orderlines = $this->oldao->getOrderLinesByOrder($order->id());
	  foreach($orderlines as $ol){
		  $ol= $this->hydrateOrderLine($ol);
	  }
	  $customer= $this->custdao->getCustomerById($order->fk_customer());
	  $order->setList_ol($orderlines);
	  $order->setCustomer($customer);
	  return $order;
  }
  public function hydrateOrderLine($orderline){
	  $product= $this->pdao->getProductById($orderline->fk_product());
	  $orderline->setProduct($product);
	  return $orderline;
  }
}