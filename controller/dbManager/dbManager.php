<?php
require_once './model/AnimationDAO.php';
require_once './model/OrganizationDAO.php';
require_once './model/ProductDAO.php';
require_once './model/CategoryDAO.php';
require_once './model/SupplierDAO.php';
require_once './model/OrderDAO.php';
require_once './model/OrderLineDAO.php';
require_once './model/ContactDAO.php';
require_once './model/CustomerDAO.php';			// A supprimer...
require_once './model/UserDAO.php';
require_once './model/MemberDAO.php';
require_once './model/ProductDAO.php';
require_once './model/EventDAO.php';
require_once './model/StockDAO.php';
require_once './model/PaymentDAO.php';

class DbManager {
	private $db_connect;
	public function __construct() {
		global  $db_string, $db_user, $db_password;
		$this->db_connect = new PDO($db_string, $db_user, $db_password);
	}
	//GET DAO
		public function loadDAO($table){
			$tableDAO = ucfirst($table)."DAO";
			return new $tableDAO($this->db_connect);
		}
	// MODE ADMIN
		public function getALL($objectCLASS){
			$classname= ($objectCLASS == "category")
					?	"categories"
					:	$objectCLASS."s";
			$loadClass= "load".ucfirst($classname);
			$data[$classname]= ($classname=="products")
					?	$this->$loadClass(null, false)
					:	$this->$loadClass();
			return $data;
		}
	// EDITORS
		// CREATOR
			public function create($target){
				$target_class= get_Class($target);
				$createClass= "create".ucfirst($target_class);
				$result= $this->loadDAO($target_class)->$createClass($target);
				return $result;
			}
			// ADHESION
			public function createUserAdhesion($user){
				$this->loadDAO("user")->createUserAdhesion($user);
				$user= $this->loadUser($user->id());
				return $user;
			}
			// ADDRESS
			public function createUserAddress($user, $address){
				$this->loadDAO("user")->createUserAddress($user);
			}
			// CONTACT
			public function createClassContact($contact){
				$classContact= ( get_class($contact) == "OrganizationContact")
					?	$this->loadDAO("organization")->createOrganizationContact($contact)
					:	$this->loadDAO("supplier")->createSupplierContact($contact);
				return $classContact;
			}
		// UPDATOR
			public function update($target){
				$target_class= get_Class($target);
				$updateClass= "update".ucfirst($target_class);
				$result= $this->loadDAO($target_class)->$updateClass($target);
				return $result;
			}
		// SUPRESSOR
			public function remove($target){
				$target_class= get_Class($target);
				$deleteClass= "delete".ucfirst($target_class);
				$result= $this->loadDAO($target_class)->$deleteClass($target);
				return $result;
			}
		//USERS
			public function createUser($user){
				$user= $this->loadDAO("user")->createUser($user);
				return $user;
			}
			public function updateUser($user){
				$user= $this->loadDAO("user")->updateUser($user);
				return $user;
			}
	//LOADERS
		// ANIMATIONS
			public function loadAnimations($animationsIDs=NULL){
				if(!$animationsIDs){
					$animations= $this->loadDAO("animation")->getAnimations();
				}
				else{
					foreach($animationsIDs as $animationID){
						$animations[]= $this->loadDAO("animation")->getAnimationById($animationID);
					}
				}
				return $animations;
			}
		//CATEGORIES
			public function loadCategories($parentID=NULL){ // search under cat. (for admin mode)
				$catDAO= $this->loadDAO("category");
				$proDAO= $this->loadDAO("product");
				if(!$parentID){																// If no categorie specified
					$categories= $catDAO->getCategories();										// Then Load all categories
					foreach($categories as $category){											// Foreach categorie :
						$badge= 0;
						$children= $catDAO->getCategoriesByParent($category->id()); 					// Load children categories list
						foreach( $children as $child){
							$child->setProducts($this->loadProducts($child->id()));
							$badge+= count($child->products());
						}
						$category->setChildCats($children);											//  Set categorie's children categories list
						$category->setBadge($badge);
					}
				}
				else{																		// Else (If categorie specified) 
					$categories= $catDAO->getCategoriesByParent($parentID);						// Then Load children categories list
					foreach($categories as $childCat){											// Foreach child
						$childCat->setProducts($this->loadProducts($childCat->id()));				// Load products list
						$childCat->setBadge(count($childCat->products()));
					}
				}
				return $categories;
			}
			public function loadCategory($catID){
				$catDAO= $this->loadDAO("category");
				$category= $catDAO->getCategoryById($catID);
				$category->setChildCats($this->loadCategories($category->id()));
				$category->setProducts(0);
				if($category->childCats()){
					foreach ($category->childCats() as $cat){
						foreach( $cat->products() as $p) $allproducts[]= $p;
					}
					$category->setProducts($allproducts);
				}
				else{
					$category->setProducts($this->loadProducts($category->id()));
				}
				$category->setBadge(count($category->products()));
				return $category;
			}
		// CONTACTS
			public function loadContacts(){
				$contacts= $this->loadDAO("contact")->getContacts();
				return $contacts;
			}
		// EVENTS
			public function loadEvents(){
				$events= $this->loadDAO("event")->getEvents();
				foreach($events as $event){
					$organizer= $this->loadOrganization($event->fk_organizer());
					$animations= $this->loadAnimations($event->animations());
					$exposants= $this->loadMembers($event->id());
					$event->setOrganizer($organizer);
					$event->setAnimations($animations);
					$event->setExposants($exposants);
				}
				return $events;
			}
			public function loadEvent($eventID){	// CAREFULLY : Can be string "Name" in case of search.
				$event= $this->loadDAO("event")->getEvent($eventID);
				$organizer= $this->loadOrganization($event->fk_organizer());
				$animations= $this->loadAnimations($event->animations());
				$exposants= $this->loadMembers($event->id());
				$event->setOrganizer($organizer);
				$event->setAnimations($animations);
				$event->setExposants($exposants);
				return $event;
			}
		//MEMBERS
			public function loadMembers($eventID=NULL){
				if(!$eventID){
					$members= $this->loadDAO("member")->getMembers();
				}
				else{
					$members= $this->loadDAO("member")->getMembersByEventId($eventID);
				}
				return $members;
			}
			public function loadMember($memberID){
				$member= $this->loadDAO("member")->getMemberById($memberID);
				return $member;
			}
		//ORDERS
			public function loadOrder($ref=NULL){
				if($ref!=NULL){
					$order= $this->loadDAO("order")->getOrderByRef($ref);
					$loadCustomer= "load".$order->customer_type();
					$customer= $this->$loadCustomer($order->fk_customer());
					$orderlines= $this->loadOrderLines($order);
					$order->setList_ol($orderlines);
					$order->setCustomer($customer);
				}
				return $order;
			}
			public function loadOrders($target=NULL, $status=NULL){
				if($target!=NULL){ // load order for this target...
					$orders= $this->loadDAO("order")->getOrdersByCustomer($target, $status);
					foreach($orders as $order){
						$orderlines= $this->loadOrderLines($order);
						$order->setList_ol($orderlines);
					}
					return $orders;
				}
				else{ // load all orders...
					$typeList= array("user", "contact", "organization");
					foreach( $typeList as $type){
						$data[$type][]= $this->loadOrdersByCustomerType($type);
					}
					return $data;
				}
			}
			public function loadOrdersByCustomerType($customer_type){
				$orders= $this->loadDAO("order")->getOrders($customer_type);
				foreach($orders as $order){
					$loadCustomer= "load".$order->customer_type();
					$customer= $this->$loadCustomer($order->fk_customer());
					$orderlines= $this->loadOrderLines($order);
					$order->setCustomer($customer);
					$order->setList_ol($orderlines);
				}
				return $orders;
			}
			// ORDERLINES
				public function loadOrderLines($order){
					$olDAO= $this->loadDAO("orderLine");
					$orderlines= $olDAO->getOrderLinesByOrder($order->id());
					foreach($orderlines as $ol){
						$product= $this->loadDAO("product")->getProductById($ol->fk_product());
						$ol->setProduct($product);
					}
					return $orderlines;
				}
		// ORGANIZATIONS
			public function loadOrganizations(){
				$organizations= $this->loadDAO("organization")->getOrganizations();
				return $organizations;
			}
			public function loadOrganization($orgaID=NULL){
					$organization= $this->loadDAO("organization")->getOrganizationById($orgaID);
					return $organization;
			}
		//PRODUCTS
			public function loadProducts($catID=NULL, $filters=false){			// depends on catID value INT
				$proDAO= $this->loadDAO("product");
				$supDAO= $this->loadDAO("supplier");
				$catDAO= $this->loadDAO("category");
				//$stockDAO= $this->loadDAO("stock");
				if(!$filters){
					$products= (!$catID)
						?	$proDAO->getProducts()
						:	$proDAO->getProductsByCategory($catID);
				}else{
					$products= $proDAO->getProductsFilters();
				}
				foreach($products as $p){
					$p->setSupplier($supDAO->getSupplierById($p->fk_supplier()));
					$p->setCategory($catDAO->getCategoryById($p->fk_cat()));
					//$p->setQuantity($stockDAO->getQuantity($p));
				}
				return $products;
			}
			public function loadProduct($productID){ // CAREFULLY : Can be string "REF" in case of adhesion.
				if(is_int($productID)){
					$product= $this->loadDAO("product")->getProductById($productID);
				}
				elseif(is_string($productID)){
					$product= $this->loadDAO("product")->getProductByRef($productID);
				}
				$product->setSupplier($this->loadSupplier($product->fk_supplier()));
				return $product;
			}
		// SUPPLIERS
			public function loadSuppliers(){
				$suppliers= $this->loadDAO("supplier")->getSuppliers();
				foreach( $suppliers as $supplier){
					$supplier->setProducts($this->loadDAO("product")->getProductsBySupplier($supplier->id()));
				}
				return $suppliers;
			}
			public function loadSupplier($supplierID){
				$supplier= $this->loadDAO("supplier")->getSupplierById($supplierID);
				$supplier->setProducts($this->loadDAO("product")->getProductsBySupplier($supplier->id()));
				return $supplier;
			}
		// USERS
			public function loadUsers(){
				$users= $this->loadDAO("user")->getUsers();
				foreach($users as $user){
					$user->setOrders($this->loadOrders($user));
				}
				return $users;
			}
			public function loadUser($userID){
				$user= $this->loadDAO("user")->getUserById($userID);
				$user->setOrders($this->loadOrders($user));
				return $user;
			}
			public function loadUserByEmail($userEmail){
				$user= $this->loadDAO("user")->getUserByEmail($userEmail);
				$user->setOrders($this->loadOrders($user));
				return $user;
			}
	// LOGIN
		//ADMIN
			public function logAdmin($target){
				$adminDAO= $this->loadDAO("admin");
				$admin= $adminDAO->login($target);
				return $admin;
			}
}