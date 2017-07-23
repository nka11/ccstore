<?php
require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/class/billingPDF.class.php';
require_once './model/AdminDAO.php';
class AdminController extends AbstractController {

  /**
   * @Route("/")
   * @Method("GET")
   */
  public function indexAction(){
	 $is_admin= $this->check_session_status();
	  if($is_admin){
		return parent::render('admin/base_admin.html');
	}
	else{
		return parent::render('admin/admin_connect.html');
	}
  }
	/**
   * @Route("/asso")
   * @Method("GET")
   */
   public function adminAssoAction(){
	    $is_admin= $this->check_session_status();
		if($is_admin){
		   $asso= $this->adao->getAsso();
		   return parent::render('admin/admin_list_asso.html', array(
			"asso"=>$asso));
	   }
   }
  /**
   * @Route("/product")
   * @Method("GET")
   */
  public function adminProductAction() {
	  $is_admin= $this->check_session_status();
	  if($is_admin){
		  $products= $this->pdao->getProducts(true);
		  foreach( $products as $product){
			  $fk_cat= $product->fk_cat();
			  $fk_supplier= $product->fk_supplier();
			  $product->setCategory($this->cdao->getCategoryById((int)$fk_cat));
			  $product->setSupplier($this->sdao->getSupplierById((int)$fk_supplier));
		  }
		return parent::render('/admin/admin_list_product.html', array(
		"products" => $products));
	  }
  }
  
  /**
   * @Route("/supplier")
   * @Method("GET")
   */
   public function adminSupplierAction(){
	    $is_admin= $this->check_session_status();
		if($is_admin){
		   $suppliers = $this->sdao->getSuppliers();
		   return parent::render('admin/admin_list_supplier.html', array(
		   "suppliers" => $suppliers));
	   }
   }
   
   /**
    * @Route("/category")
	* @Method("GET")
	*/
	public function adminCategoryAction(){
		 $is_admin= $this->check_session_status();
		if($is_admin){
			$categories= $this->loadCategories();
			return parent::render('admin/admin_list_category.html', array(
			"categories"	=> $categories));
		}
	}
	/**
   * @Route("/order")
   * @Method("GET")
   */
   public function adminOrderAction(){
	    $is_admin= $this->check_session_status();
		if($is_admin){
		   $orders= $this->loadOrders();
		   return parent::render('admin/admin_list_order.html', array(
											"orders"=> $orders
											));
	   }
   }
   /**
    * @Route("/customer")
	* @Method("GET")
	*/
	public function adminCustomerAction(){
		$is_admin= $this->check_session_status();
		if($is_admin){
			$customers= $this->loadCustomers();
			return parent::render('admin/admin_list_customer.html', array(
												"customers"=> $customers
												));
		}
	}
   /**
    *
	* @Route("/connect")
	* @Method("GET")
	*/
   public function connectAction(){
	   return parent::render('admin/admin_connect.html');
   }
   /**
    *
	* @Route("/add/customer(/:id)")
	* @Method("GET")
	*/
	public function adminFormCustomerAction($id=false){
		$is_admin= $this->check_session_status();
		if($is_admin){
			if($id){
				 $customer= $this->custdao->getCustomerById($id);
				 $action= array(
					"button_value"=>"Modifier",
					"mode"=>"edit");
			 }else{
				$customer=null;
				$action= array(
				"button_value"=>"Ajouter",
				"mode"=>"add");
			 }
			 return parent::render('admin/admin_customer.html', array(
				"customer" => $customer,
				"action"=>$action));
		}
	}
	/**
	 * @Route("/add/product(/:id)")
	 * @Method("GET")
	 */
	 public function adminFormProductAction($id=false){
		  $is_admin= $this->check_session_status();
			if($is_admin){
			 $suppliers= $this->sdao->getSuppliers();
			 $categories= $this->cdao->getCategories();
			 if($id){
				 $product= $this->pdao->getProductById($id);
				 $action= array(
					"button_value"=>"Modifier",
					"mode"=>"edit");
			 }else{
				$product=array();
				$action= array(
				"button_value"=>"Ajouter",
				"mode"=>"add");
			 }
			 return parent::render('admin/admin_product.html', array(
				"suppliers" => $suppliers,
				"categories" => $categories,
				"product" => $product,
				"action"=>$action));
		 }
	 }
	 /**
	  *
	  * @Route("/add/category(/:id)")
	  * @Method("GET")
	  */
	  public function adminFormCategoryAction($id=false){
		  $is_admin=$this->check_session_status();
		  if($is_admin){
			  if($id && $id>0 && $id!=null && $id!=""){
				  $category= $this->cdao->getCategoryById($id);
				  $action=array(
						"button_value"	=>	"Modifier",
						"mode"					=> "edit");
			  }
			  else{
				  $category=null;
				  $action= array(
						"button_value"	=>	"Ajouter",
						"mode"					=>	"add");
			  }
			  $categories= $this->loadCategories();
			  return parent::render('admin/admin_category.html', array(
					"categories"	=> $categories,
					"category"		=> $category,
					"action"			=> $action));
		  }
	  }
	/**
	 * @Route("/add/supplier(/:id)")
	 * @Method("GET")
	 */
	 public function adminFormSupplierAction($id=false){
		 $is_admin= $this->check_session_status();
		if($is_admin){
			 if($id){
				 $supplier= $this->sdao->getSupplierById($id);
				 $action= array(
					"button_value"=>"Modifier",
					"mode"=>"edit");
			 }else{
				 $supplier= array();
				 $action= array(
				"button_value"=>"Ajouter",
				"mode"=>"add");
			 }
			 return parent::render('admin/admin_supplier.html', array(
				"supplier"=>$supplier,
				"action"=>$action));
		}
	}
	/**
	 *
	 * @Route("/add/order(/:ref)")
	 * @Method("GET")
	 */
	 public function adminFormOrderAction($ref=false){
		$is_admin= $this->check_session_status();
		if($is_admin){
			if($id){
				$order= $this->odao->getOrderByRef($ref);
				$action= array(
				"button_value"=>"Modifier",
				"mode"=>"edit");
			}else{
				$order= array();
				$action= array(
				"button_value"=>"Ajouter",
				"mode"=>"add");
			}
			return parent::render('admin/admin_order.html', array(
				"order"=>$order,
				"action"=>$action));
		}
	 }
	/**
	 *
	 * @Route("/create/admin")
	 * @Method("GET")
	 */
	 public function createAdminAction(){
		 return parent::render('admin/admin_create.html');
	 }
	 /**
	  *
	  * @Route("/post/admin")
	  * @Method("POST")
	  */
	  public function postAdminAction(){
		 $pseudo=null;
		 $password=null;
		 if(array_key_exists('pseudo', $_POST)
			 && array_key_exists('password', $_POST)){
			$pseudo= (!empty($_POST['pseudo']))
						? htmlentities($_POST['pseudo'])
						: null;
			$password= (!empty($_POST['password']))
						? htmlentities($_POST['password'])
						: null;
			if($pseudo && $pseudo!=null && $pseudo!=""
				&& $password && $password!=null && $password!=""){
				$admin= new Admin(array(
						"id_a"			=>null,
						"pseudo"		=> $pseudo,
						"password"	=> $password
						));
				$admin= $this->adao->createAdmin($admin);
				if($admin){
					return parent::render('admin/admin_success.html');
				}
			}
			else{	// Unvalid parameters
				$message = "Parametres non valide";
				http_response_code(403); //Unauthorized
				return parent::render("error/403.html", array("message"=> $message));	
			}
		}
		else{	// Unvalid parameters
			$message = "Formulaire incomplet";
			http_response_code(403); //Unauthorized
			return parent::render("error/403.html", array("message"=> $message));	
		}
	  }
	/**
	 *
	 * @Route("/login")
	 * @Method("POST")
	 */
	 public function loginAction(){
		 $pseudo=null;
		 $password=null;
		 if(array_key_exists('pseudo', $_POST)
			 && array_key_exists('password', $_POST)){
			$pseudo= (!empty($_POST['pseudo']))
						? htmlentities($_POST['pseudo'])
						: null;
			$password= (!empty($_POST['password']))
						? htmlentities($_POST['password'])
						: null;
			if($pseudo && $pseudo!=null && $pseudo!=""
				&& $password && $password!=null && $password!=""){
				$admin= new Admin(array(
							"id_a"			=>null,
							"pseudo"		=> $pseudo,
							"password"	=> $password
							));
				$admin= $this->adao->login($admin);
				if($admin){
					$_SESSION['status']= 'admin';
					return parent::render('admin/base_admin.html');
				}
				else{ // Login failure
					$message = "Email ou mot de passe invalide";
					http_response_code(403); //Unauthorized
					return parent::render("error/403.html", array("message"=> $message));
				}
			}
			else{	// Unvalid parameters
				$message = "Parametres non valide";
				http_response_code(403); //Unauthorized
				return parent::render("error/403.html", array("message"=> $message));	
			}
		}
		else{	// Unvalid parameters
			$message = "Formulaire incomplet";
			http_response_code(403); //Unauthorized
			return parent::render("error/403.html", array("message"=> $message));	
		}
	 }
	/**
	 * @Route("/post/category")
	 * @Method("POST")
	 */
	 public function postCategoryAction(){
		  $is_admin= $this->check_session_status();
	  if($is_admin){
			$id_category=null;
			 $label=NULL;
			 $description=NULL;
			 $id_parent= NULL;
			 $mode= NULL;
			 if(array_key_exists('label', $_REQUEST)
				&& array_key_exists('description', $_REQUEST)
					&& array_key_exists('id_parent', $_REQUEST)
					 && array_key_exists('mode', $_REQUEST)){
					$label = (!empty($_REQUEST['label'])) 
					? htmlentities($_REQUEST['label'])
					: false;
					$description = (!empty($_REQUEST['description']))
					? htmlentities($_REQUEST['description'])
					: false;
					$id_parent= (!empty($_REQUEST['id_parent']))
					? htmlentities($_REQUEST['id_parent'])
					: null;
					$mode=$_REQUEST['mode'];
					// verification des parametres
					if ($label && $label != null && $label != "" 
					 && $description && $description != null && $description != ""){
						 $category = new Category (array(
							"id"				=> $id_category,
							"id_parent"=>$id_parent,
							"label"=>$label,
							"description"=>$description						
							));

						if($mode == "add"){
						 try{
							$category = $this->cdao->createCategory($category);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						}elseif($mode="edit"){
							try{
							$category->setId((int)$_REQUEST['id_category']);
							$category = $this->cdao->updateCategory($category);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						}
						if($category) {
							// New category created successfully
						 return parent::render("admin/admin_success.html",  array("category"=>$category));
						}
					 }
					 else{
						 $message="Parametre(s) manquant(s) ou invalide(s)";
						 http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					 }
				}
				else{
					$message="Formulaire incomplet";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
		 }
	 }
	 /**
	  *
	  * @Route("post/contact")
	  * @Method("POST")
	  */
	public function postContactAction(){
		$is_admin= $this->check_session_status();
		if($is_admin){
			$label=NULL;
			$email=null;
			$lastname=NULL;
			$name=null;
			$zip=null;
			$town=null;
			$phone=NULL;
			$mode=null;
		}
		if(array_key_exists('label', $_POST)
			&& array_key_exists('email', $_POST)
			 && array_key_exists('lastname', $_POST)
			  && array_key_exists('name', $_POST)
			   && array_key_exists('address', $_POST)
			    && array_key_exists('zip', $_POST)
				 && array_key_exists('town', $_POST)
				  && array_key_exists('phone', $_POST)){
		
		}
		
	}
	 /**
	 * @Route("/post/supplier")
	 * @Method("POST")
	 */
	public function postSupplierAction(){
		 $is_admin= $this->check_session_status();
	  if($is_admin){
			 $label=NULL;
			 $name=NULL;
			 $zip=NULL;
			 $town=NULL;
			 $mode=NULL;
			 if(array_key_exists('label', $_REQUEST)
				&& array_key_exists('name', $_REQUEST)
				&& array_key_exists('zip', $_REQUEST)
				&& array_key_exists('town', $_REQUEST)
				&& array_key_exists('mode', $_REQUEST)){
					$label = (!empty($_REQUEST['label'])) 
					? htmlentities($_REQUEST['label'])
					: false;
					$name = (!empty($_REQUEST['name']))
					? htmlentities($_REQUEST['name'])
					: false;
					$zip = (!empty($_REQUEST['zip']))
					? htmlentities($_REQUEST['zip'])
					: false;
					$town = (!empty($_REQUEST['town']))
					? htmlentities($_REQUEST['town'])
					: false;
					$mode=$_REQUEST['mode'];
					// verification des parametres
					if ($label && $label != null && $label != "" 
					 && $name && $name != null && $name != ""
					 && $zip && $zip !=null && $zip != ""
					 && $town && $town != null && $town != ""){
						 $supplier = new Supplier (array(
							"label"=>$label,
							"name"=>$name,
							"zip"=>$zip,
							"town"=>$town
							));
						 if($mode == "add"){
						 try{
							$supplier = $this->sdao->createSupplier($supplier);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						 }elseif($mode="edit"){
							 try{
								 $supplier->setId((int)$_REQUEST['id_supplier']);
								$supplier = $this->sdao->editSupplier($supplier);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						 }
						if($supplier) {
							// New category created successfully
						 return parent::render("admin/admin_success.html",  array("supplier"=>$supplier));
						}
					 }
					 else{
						 $message="Parametre(s) manquant(s) ou invalide(s)";
						 http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					 }
				}
				else{
					$message="Parametre(s) manquant(s)";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
		}
	 }
	 
	  /**
	 * @Route("/post/product")
	 * @Method("POST")
	 */
	 public function postProductAction(){
		 $is_admin= $this->check_session_status();
	  if($is_admin){
			 $label= NULL;
			 $fk_supplier= NULL;
			 $fk_category= NULL;
			 $packaging= NULL;
			 $package_weight=NULL;
			 $weight_unit= NULL;
			 $description=NULL;
			 $status= NULL;
			 $mode=NULL;
			 $extensions_valid= array( 'jpg', 'jpeg');
			  if(array_key_exists('label', $_REQUEST)
				&& array_key_exists('fk_supplier', $_REQUEST)
				&& array_key_exists('fk_category', $_REQUEST)
				&& array_key_exists('packaging', $_REQUEST)
				&& array_key_exists('weight_unit', $_REQUEST)
				&& array_key_exists('package_weight', $_REQUEST)
				&& array_key_exists('price', $_REQUEST)
				&& array_key_exists('description', $_REQUEST)
				&& array_key_exists('status', $_REQUEST)
				&& array_key_exists('mode', $_REQUEST)
				&& array_key_exists('MAX_FILE_SIZE', $_REQUEST)){
					// Vérification du transfert de fichier
				 if($_FILES['picture']['error'] > 0 && $_REQUEST['mode'] == "add" ){
					$message="Transfert de fichier erreur : ".$_FILES['picture']['error'];
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				 }
				$label = (!empty($_REQUEST['label'])) 
					? htmlentities($_REQUEST['label'])
					: false;
				$fk_supplier = (!empty($_REQUEST['fk_supplier'])) 
					? htmlentities($_REQUEST['fk_supplier'])
					: false;
				$fk_category = (!empty($_REQUEST['fk_category'])) 
					? htmlentities($_REQUEST['fk_category'])
					: null;
				$packaging = (!empty($_REQUEST['packaging'])) 
					? htmlentities($_REQUEST['packaging'])
					: false;
				$weight_unit= $_REQUEST['weight_unit'];
				$package_weight= $_REQUEST['package_weight'];
				$price= (!empty($_REQUEST['price'])) 
					? htmlentities($_REQUEST['price'])
					: false;
				$description= (!empty($_REQUEST['description']))
						? htmlentities($_REQUEST['description'])
						: false;
				$status = (!empty($_REQUEST['status'])) 
					? htmlentities($_REQUEST['status'])
					: false;
				$weight_unit= $_REQUEST['weight_unit'];
				$mode= $_REQUEST['mode'];
				$maxsize= $_REQUEST['MAX_FILE_SIZE'];
				// Vérification des critères de l'images
				if($_FILES['picture']['size']>$maxsize){
					$message="Fichier transféré trop grand";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
				// Vérification de l'extension du fichier
				
				$extension_upload= strtolower(substr(strrchr($_FILES['picture']['name'], ".") ,1));
				
				if(($mode=="edit" && $extension_upload!="") || ($mode=="add")){
					if (! in_array($extension_upload, $extensions_valid)){
						$message="Extension (".$extension_upload.") du fichier n'est pas admise.";
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					}
				}

				// verification des parametres
					if ($label && $label != null && $label != "" 
					 && $fk_supplier && $fk_supplier != null && $fk_supplier != ""
					 && $packaging && $packaging != null && $packaging != ""
					 && $price && $price != null && $price != ""
					 && $weight_unit && $weight_unit != null && $weight_unit != ""
					 && $description && $description != null && $description != ""
					 && $status && $status != null && $status != ""){
						 $product = new Product (array(
							"label"=>$label,
							"fk_supplier"=>(int) $fk_supplier,
							"fk_cat"=>(int) $fk_category,
							"packaging"=>$packaging,
							"package_weight"=>$package_weight,
							"weight_unit"=>$weight_unit,
							"price"=>(float) $price,
							"description"	=> $description,
							"status"=>$status
							));
						 if($mode=="add"){
						 try{
							$product = $this->pdao->createProduct($product);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						 }elseif($mode=="edit"){
							  try{
							$product->setId((int)$_REQUEST['id_product']);
							$product = $this->pdao->editProduct($product);
							}
							catch(Throwable $t){
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $t));
							}
						 }
						if($product) {
							// New product created successfully
							if($mode == 'add' || ($_FILES['picture']['error'] == 0 && $mode=="edit")){
									$filename= "files/pictures/product/".$product->id().".".$extension_upload;
									$movefile= move_uploaded_file($_FILES['picture']['tmp_name'], $filename);
								if($movefile){
									return parent::render("admin/admin_success.html");
								}else{
									$message="Produit sauvegardé mais une erreur est survenue lors de l'enregistrement de l'image du produit";
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $message));
								}
							}
							else return parent::render("admin/admin_success.html");
						}else{
								$message="Parametre(s) invalide(s) pour la base de donnée";
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $message));
						}
					 }else{
						 $message="Valeurs des parametre(s) manquant(s) ou invalide(s)";
						 http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					 }
				}
				else{
					$message="Formulaire incomplet";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
		 }
	}
	/**
	 * @Route("/del/:object/:id")
	 * @Method("GET")
	 */
	public function deleteFormConfirmAction($object, $id){
		 $is_admin= $this->check_session_status();
	  if($is_admin){
			$target= array(
				"type"=>$object,
				"id_object"=>$id);
			return parent::render("admin/admin_delConfirm.html", array(
			"object"=> $target));
		}
	}
	/**
	 * @Route("/del/:object/:id")
	 * @Method("POST")
	 */
	public function deleteObjectAction($object, $id){
		global  $db_string, $db_user, $db_password;
		$db_connect = new PDO($db_string, $db_user, $db_password);
		 $is_admin= $this->check_session_status();
	  if($is_admin){
			$methodDAO= ucfirst($object)."DAO";
			$getmethod= "get".ucfirst($object)."ById";
			$delmethod= "delete".ucfirst($object);
			$odao= new $methodDAO($db_connect);
			$target= $odao->$getmethod($id);

			try{
				$odao->$delmethod($target);
			}
			catch(Throwable $t){
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $t));
			}
			return parent::render("admin/admin_success.html");
		}
	}
	/**
	 * @Route("/pdfGenerator/:ref")
	 * @Method("GET")
	 */
	 public function pdfGeneratorAction($ref){
		$order=	(htmlentities($ref) != null) 
					?	$this->odao->getOrderByRef($ref)
					:	false;
		if($order) $order= $this->hydrateOrder($order);
		else return parent::render("error/400.html", array("message" => "Commance introuvable"));
		
		// Instanciation de la classe dérivée
		$pdf = new billingPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->orderDetail($order);
		$pdf->orderlinesDetails($order);
		$pdf->Output();
	 }
	//control function
	public function check_session_status(){
		if($this->session['status'] == 'admin'){
			return true;
		}
		else{
			return header('Location: '.$this->base_path.'/admin/connect');
		}
	}
}
