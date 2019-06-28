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
		$user_status = get_Class($this->session['user']);
		echo "Session status : ".$user_status;exit();
		if($user_status != "member" && $user_status != "Admin") return parent::render("admin/form/connect.html");
		return parent::render('admin/base_admin.html');
	}
	/**
	 *	@Route("/:object")
	 *	@Method("GET")
	 */
	public function objetAction($object){
		$user_status = get_Class($this->session['user']);
		if($user_status != "member" && $user_status != "admin") return parent::render("admin/form/connect.html");
		$data= $this->dbManager->getAll($object);
		return parent::render("admin/list/$object.html", $data);
	}
	/**
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
					"password"	=> $password));
				$admin= $this->dbManager->logAdmin($admin);
				if($admin){
					$_SESSION['status']= 'Admin';
					$_SESSION['user']=$admin;
					$this->handle_session();
					return parent::render('admin/base_admin.html');
				}
				else $alert="Email ou mot de passe non valide";
			}
			else $alert= "Parametres non valide";
		}
		else $alert= "Formulaire incomplet";
		return parent::render('admin/form/connect.html', array("alert"=>$alert));
	}
	/**
	* @Route("/logout")
	* @Method("POST")
	*/
	public function logoutAction(){
		session_destroy();
		return parent::render("/admin/form/connect.html");
	}
	/**
	 * @Route("/add/:object(/:targetID)")
	 * @Method("GET")
	 */
	public function addObjectAction($object, $targetID=NULL){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$data=array($object=>null);
		if($targetID && $targetID>0 && $targetID!=null && $targetID!=""){
				$loadObject= "load".ucfirst($object);
				$data[$object]= $this->dbManager->$loadObject((int)$targetID);
		}
		switch($object){
			case "product"	:	$data["suppliers"]= $this->dbManager->loadSuppliers();
			case "category"	:	$data["categories"]= $this->dbManager->loadCategories();
			break;
			case "event":	$data["animations"]= $this->dbManager->loadAnimations();
							$data["organizations"]= $this->dbManager->loadOrganizations();
			break;
		}
		return parent::render("admin/form/$object.html", $data);
	}
	/**
	*
	* @Route("/post/admin")
	* @Method("POST")
	*/
	public function postAdminAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
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
					"password"	=> $password));
				$admin= $this->dbManager->createAdmin($admin);
				if($admin) $alert="Admin enregistré";
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$admins= $this->dbManager->loadAdmins();
		return parent::render("admin/list/admin.html", array(
											"admins"=>$admins,
											"alert"=>$alert));
	}
	/**
	 * Route("/post/animation")
	 * @Method("POST")
	 */
	public function postAnimationAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$id_animation=null;
		$name=null;
		$default_sell_price=null;
		$description=null;
		$number_of_animators=null;
		if(array_key_exists("name", $_POST)
			&& array_key_exists("default_sell_price", $_POST)
			 && array_key_exists("description", $_POST)
			  && array_key_exists("number_of_animators", $_POST)){
			$name = (!empty($_POST['name']))
				? htmlentities($_POST['name'])
				: false;
			$default_sell_price = (!empty($_POST['default_sell_price']))
				? htmlentities($_POST['default_sell_price'])
				: false;
			$description = (!empty($_POST['description']))
				? htmlentities($_POST['description'])
				: false;
			$number_of_animators = (!empty($_POST['number_of_animators']))
				? htmlentities($_POST['number_of_animators'])
				: false;
			$mode=$_REQUEST['mode'];
			// verification des parametres
			if ($name && $name != null && $name != "" 
				  && $description && $description != null && $description != ""){
				$animation = new Animation (array(
					"name"=>$name,
					"default_sell_price"=>(int)$default_sell_price,
					"description"=>$description,
					"number_of_animators"=>(int)$number_of_animators));

				if($mode == "add") $animation = $this->dbManager->create($animation);
				elseif($mode="edit"){
						$animation->setId((int)$_POST['id_animation']);
						$animation = $this->dbManager->update($animation);
				}
				
				if($animation){
					$alert= ($mode == "add")
						?	"Enregistrement effectué."
						:	"Modification effectuée.";
				}
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$animations= $this->dbManager->loadAnimations();
		return parent::render("admin/list/animation.html", array(
										"animations"=>$animations,
										"alert"=>$alert));
	}
	/**
	* @Route("/post/category")
	* @Method("POST")
	*/
	public function postCategoryAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
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
					"id"=> $id_category,
					"id_parent"=>$id_parent,
					"label"=>$label,
					"description"=>$description));

				if($mode == "add") $category = $this->dbManager->create($category);
				elseif($mode="edit"){
						$category->setId((int)$_REQUEST['id_category']);
						$category = $this->dbManager->update($category);
				}
				
				if($category){
					$alert= ($mode == "add")
						?	"Enregistrement effectué."
						:	"Modification effectuée.";
				}
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$categories= $this->dbManager->loadCategories();
		return parent::render("admin/list/category.html", array(
										"categories"=>$categories,
										"alert"=>$alert));
	}
	/**
	*
	* @Route("/post/contact")
	* @Method("POST")
	*/
	public function postContactAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$gender=NULL;
		$lastname=NULL;
		$name=NULL;
		//$fn=NULL;
		$phone=NULL;
		$email=NULL;
		$mode=NULL;
		if(array_key_exists('gender', $_REQUEST)
			&& (array_key_exists('lastname', $_REQUEST)
			 || array_key_exists('name', $_REQUEST)
			  || array_key_exists('fn', $_REQUEST)
			   || array_key_exists('phone', $_REQUEST)
				|| array_key_exists('email', $_REQUEST))){
			$gender = (!empty($_REQUEST['gender']))
				? htmlentities($_REQUEST['gender'])
				: false;
			$lastname = (!empty($_REQUEST['lastname']))
				? htmlentities($_REQUEST['lastname'])
				: false;
			$name = (!empty($_REQUEST['name']))
				? htmlentities($_REQUEST['name'])
				: false;
			/*
			$fn = (!empty($_REQUEST['fn']))
				? htmlentities($_REQUEST['fn'])
				: false;*/
			$phone = (!empty($_REQUEST['phone']))
				? htmlentities($_REQUEST['phone'])
				: false;
			$email = (!empty($_REQUEST['email']))
				? htmlentities($_REQUEST['email'])
				: false;
			$address = (!empty($_REQUEST['address']))
				? htmlentities($_REQUEST['address'])
				: false;
			$zip = (!empty($_REQUEST['zip']))
				? htmlentities($_REQUEST['zip'])
				: false;
			$town = (!empty($_REQUEST['town']))
				? htmlentities($_REQUEST['town'])
				: false;
			$mode= $_POST["mode"];
			$contact = new Contact (array(
				"gender"=>$gender,
				"lastname"=>$lastname,
				"name"=>$name,
				//"fn"=>$fn,
				"email"=>$email,
				"phone"=>$phone,
				"address"=>$address,
				"zip"=>$zip,
				"town"=>$town));
			if($mode=="add") $contact = $this->dbManager->create($contact);
			elseif($mode=="edit"){
				$contact->setId((int)$_POST["id_contact"]);
				$contact= $this->dbManager->update($contact);
			}
			if($contact){
				$alert= ($mode=="add")
					?	"Enregistrement effectué."
					:	"Modifications effectuées.";
			}
			else $alert= "Erreur Inconnue. TRAITEMENT OU REQUETE DB";
		}
		else $alert="Parametre(s) manquant(s)";
		$contacts= $this->dbManager->loadContacts();
		return parent::render("admin/list/contact.html", array(
										"alert"=> $alert,
										"contacts"=> $contacts));
	}
	/**
	 * @Route("/post/event")
	 * @Method("POST")
	 */
	public function postEventAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$name=null;
		$price=null;
		$fk_organizer=null;
		$animations= array();
		$description=null;
		$start_date=null;
		$end_date=null;
		$kind=null;
		$place=null;
		$mode=null;
		if(array_key_exists('name', $_POST)
			&& array_key_exists('kind', $_POST)
			 && array_key_exists('price', $_POST)
			  && array_key_exists('fk_organizer', $_POST)
			   && array_key_exists('address', $_POST)
				&& array_key_exists('start_date', $_POST)
				 && array_key_exists('end_date', $_POST)
				  && array_key_exists('zip', $_POST)
				   && array_key_exists('town', $_POST)
				    && array_key_exists('countAnimations', $_POST)){
			$name= $_POST['name'];
			$price= $_POST['price'];
			$kind= $_POST['kind'];
			$fk_organizer= $_POST['fk_organizer'];
			$event_address= array(
								"address"=>$_POST['address'],
								"zip"=>$_POST['zip'],
								"town"=>$_POST['town']
							);
			$description= $_POST['description'];
			$start_date= $_POST['start_date'];
			$end_date= $_POST['end_date'];
			$place= array( "address"=>$_POST['address'],
							"zip"=>$_POST['zip'],
							"town"=>$_POST['town']);
			if($_POST['countAnimations']==1) $animations= array($_POST['fk_animation']);
			else{
				for( $i = 1; $i <= $_POST['countAnimations']; $i++){
					$animations[]= $_POST["fk_animation$i"];
				}
			}
			$mode= $_POST['mode'];
			if($name && $name!="" && $name!=null
				&& $kind && $kind!="" && $kind!=null
				 && $price && $price!="" && $price!=null
				  && $fk_organizer && $fk_organizer!="" && $fk_organizer!=null
				   && $start_date && $start_date!="" && $start_date!=null
				    && $animations && $animations!="" && $animations!=null
					 && $end_date && $end_date!="" && $end_date!=null){
				$event= new Event( array(
					"name"=> $name,
					"price"=>$price,
					"fk_organizer"=>$fk_organizer,
					"animations"=>$animations,
					"description"=>$description,
					"start_date"=>$start_date,
					"end_date"=>$end_date,
					"place"=>$place
				));
				if($mode == "add")	$event= $this->dbManager->create($event);
				elseif($mode == "edit"){
					$event->setId((int)$_POST["id_event"]);
					$event= $this->dbManager->update($event);
				}
				if($event) $alert="Evenement enregistré";
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$events= $this->dbManager->loadEvents();
		return parent::render("admin/list/event.html", array(
											"events"=>$events,
											"alert"=>$alert
									));
	}
	/**
	 * @Route("/post/member")
	 * @Method("POST")
	 */
	public function postMemberAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$lastname=NULL;
		$name=null;
		$email=null;
		$phone=NULL;
		$fn=NULL;
		$address=null;
		$zip=null;
		$town=null;
		$mode=NULL;
		if( array_key_exists('lastname', $_POST)
			 && array_key_exists('name', $_POST)
			  && array_key_exists('fn', $_POST)){
			$email= ($_POST['email'])
				?	$_POST['email']
				:	null;
			$lastname= ucfirst($_POST['lastname']);
			$fn= $_POST['fn'];
			$name= ucfirst($_POST['name']);
			$address= ($_POST['address'])
				?	htmlentities($_POST['address'])
				:	null;
			$zip= ($_POST['zip'])
				?	$_POST['zip']
				:	null;
			$town= ($_POST['town'])
				?	htmlentities($_POST['town'])
				:	null;
			$phone= ($_POST['phone'])
				?	$_POST['phone']
				:	null;
			$mode= $_POST['mode'];
			if( $lastname && $lastname!="" && $lastname!=null
				 && $name && $name!="" && $name!=null
				  && $fn && $fn!="" && $fn!=null){
				$member= new Member(array(
					"email"		=>	$email,
					"lastname"	=> 	$lastname,
					"name"		=>	$name,
					"fn"		=>	$fn,
					"address"	=> 	$address,
					"zip"		=>	$zip,
					"town"		=>	$town,
					"phone"		=>	$phone));
				if($mode == "add") $member = $this->dbManager->create($member);
				elseif($mode == "edit"){
					$member->setId((int)$_POST["id_member"]);
					$member= $this->dbManager->update($member);
				}
				if($member){
					$alert= ($mode == "add")
						?	"Enregistrement effectué."
						:	"Modification effectuée.";
				}
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$members= $this->dbManager->loadMembers();
		return parent::render("admin/list/member.html", array(
										"members"=>$members,
										"alert"=>$alert));
	}
	/**
	* @Route("/post/organization")
	* @Method("POST")
	*/
	public function postOrganizationAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$label=null;
		$kind=null;
		$siren=null;
		$address=null;
		$zip=null;
		$town=null;
		$email=null;
		$mode=null;
		if(array_key_exists('label', $_POST)
			&& array_key_exists('kind', $_POST)
			 && array_key_exists('siren', $_POST)
			  && array_key_exists('email', $_POST)
			   && array_key_exists('address', $_POST)
				&& array_key_exists('zip', $_POST)
				 && array_key_exists('town', $_POST)
				  && array_key_exists('phone', $_POST)){
			$email= $_POST['email'];
			$label= ucfirst($_POST['label']);
			$kind= ucfirst($_POST['kind']);
			$siren= $_POST['siren'];
			$address= $_POST['address'];
			$zip= $_POST['zip'];
			$town= $_POST['town'];
			$phone= $_POST['phone'];
			$mode= $_POST['mode'];
			if($label && $label!="" && $label!=null
				&& $kind && $kind!="" && $kind!=null
				  && $zip && $zip!="" && $zip!=null
				    && $town && $town!="" && $town!=null){
				$organization= new Organization(array(
					"label"		=>	$label,
					"kind"		=>	$kind,
					"siren"		=>	$siren,
					"email"		=>	$email,
					"address"	=> 	$address,
					"zip"		=>	$zip,
					"town"		=>	$town,
					"phone"		=>	$phone));
				if($mode=="add") $organization = $this->dbManager->create($organization);
				elseif($mode=="edit"){
					$organization->setId((int)$_POST["id_organization"]);
					$organization= $this->dbManager->update($organization);
				}
				if($organization){
					$alert= ($mode == "add")
						?	"Enregistrement effectué."
						:	"Modification effectuée.";
				}
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		$organizations= $this->dbManager->loadOrganizations();
		return parent::render("admin/list/organization.html", array(
										"organizations"=>$organizations,
										"alert"=>$alert));
	}
	/**
	* @Route("/post/organizationContact/:orgaID")
	* @Method("POST")
	*/
	public function postOrganizationContactAction($orgaID){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$fk_organization= (int) $orgaID;
		$gender=NULL;
		$lastname=NULL;
		$name=NULL;
		$fn=NULL;
		$phone=NULL;
		$email=NULL;
		$mode=NULL;
		if(array_key_exists('gender', $_REQUEST)
			&& (array_key_exists('lastname', $_REQUEST)
			 || array_key_exists('name', $_REQUEST)
			  || array_key_exists('fn', $_REQUEST)
			   || array_key_exists('phone', $_REQUEST)
				|| array_key_exists('email', $_REQUEST))){
			$gender = (!empty($_REQUEST['gender']))
				? htmlentities($_REQUEST['gender'])
				: false;
			$lastname = (!empty($_REQUEST['lastname']))
				? htmlentities($_REQUEST['lastname'])
				: false;
			$name = (!empty($_REQUEST['name']))
				? htmlentities($_REQUEST['name'])
				: false;
			$fn = (!empty($_REQUEST['fn']))
				? htmlentities($_REQUEST['fn'])
				: false;
			$phone = (!empty($_REQUEST['phone']))
				? htmlentities($_REQUEST['phone'])
				: false;
			$email = (!empty($_REQUEST['email']))
				? htmlentities($_REQUEST['email'])
				: false;
			$address = (!empty($_REQUEST['address']))
				? htmlentities($_REQUEST['address'])
				: false;
			$zip = (!empty($_REQUEST['zip']))
				? htmlentities($_REQUEST['zip'])
				: false;
			$town = (!empty($_REQUEST['town']))
				? htmlentities($_REQUEST['town'])
				: false;
			$mode= $_POST["mode"];
			$organizationContact = new OrganizationContact (array(
				"fk_organization"=>$fk_organization,
				"gender"=>$gender,
				"lastname"=>$lastname,
				"name"=>$name,
				"fn"=>$fn,
				"email"=>$email,
				"phone"=>$phone,
				"address"=>$address,
				"zip"=>$zip,
				"town"=>$town));
			if($mode=="add") $organizationContact = $this->dbManager->createClassContact($organizationContact);
			elseif($mode=="edit"){
				$organizationContact->setId((int)$_POST["id_organizationContact"]);
				$organizationContact= $this->dbManager->updateClassContact($organizationContact);
			}
			if($organizationContact){
				$alert= ($mode=="add")
					?	"Enregistrement effectué."
					:	"Modifications effectuées.";
			}
			else $alert= "Erreur Inconnue. TRAITEMENT OU REQUETE DB";
		}
		else $alert="Parametre(s) manquant(s)";
		$organizations= $this->dbManager->loadOrganizations();
		return parent::render("admin/list/organization.html", array(						//OR $this->adminSupplierAction($alert);
										"alert"=> $alert,
										"organizations"=> $organizations));
	}
	/**
	* @Route("/post/product")
	* @Method("POST")
	*/
	public function postProductAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$alert=null;
		$label= NULL;
		$ref=NULL;
		$agriculture= NULL;
		$fk_supplier= NULL;
		$fk_category= NULL;
		$packaging= NULL;
		$package_weight=NULL;
		$weight_unit= NULL;
		$description=NULL;
		$visibility= NULL;
		$availability= NULL;
		$mode=NULL;
		$extensions_valid= array( 'jpg', 'jpeg');
		if(array_key_exists('label', $_REQUEST)
			&& array_key_exists('ref', $_REQUEST)
			 && array_key_exists('agriculture', $_REQUEST)
			  && array_key_exists('fk_supplier', $_REQUEST)
			   && array_key_exists('fk_category', $_REQUEST)
				&& array_key_exists('packaging', $_REQUEST)
				 && array_key_exists('weight_unit', $_REQUEST)
				  && array_key_exists('package_weight', $_REQUEST)
				   && array_key_exists('price', $_REQUEST)
					&& array_key_exists('description', $_REQUEST)
					 && array_key_exists('visibility', $_REQUEST)
					  && array_key_exists('availability', $_REQUEST)
					   && array_key_exists('mode', $_REQUEST)
					    && array_key_exists('MAX_FILE_SIZE', $_REQUEST)){
			$label = (!empty($_REQUEST['label'])) 
				? htmlentities($_REQUEST['label'])
				: false;
			$ref = (!empty($_REQUEST['ref']))
				? htmlentities($_REQUEST['ref'])
				: false;
			$agriculture = (!empty($_REQUEST['agriculture'])) 
				? htmlentities($_REQUEST['agriculture'])
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
			$supplier_price= (!empty($_REQUEST['supplier_price']))
				? htmlentities($_REQUEST['supplier_price'])
				: 0;
			$description= (!empty($_REQUEST['description']))
				? htmlentities($_REQUEST['description'])
				: false;
			$visibility = (!empty($_REQUEST['visibility']) || $_REQUEST['visibility']==0)
				? htmlentities($_REQUEST['visibility'])
				: false;
			$availability = (!empty($_REQUEST['availability']) || $_REQUEST['availability']==0)
				? htmlentities($_REQUEST['availability'])
				: false;				
			$weight_unit= $_REQUEST['weight_unit'];
			$mode= $_REQUEST['mode'];
			$maxsize= $_REQUEST['MAX_FILE_SIZE'];
			// Vérification du transfert de fichier
			if($_FILES['picture']['error'] > 0 && $_REQUEST['mode'] == "add" ){
				$alert="Transfert de fichier erreur : ".$_FILES['picture']['error'];
			}
			// Vérification des critères de l'images
			if($_FILES['picture']['size']>$maxsize) $alert="Fichier transféré trop grand";
			// Vérification de l'extension du fichier
			$extension_upload= strtolower(substr(strrchr($_FILES['picture']['name'], ".") ,1));
			if(($mode=="edit" && $extension_upload!="") || ($mode=="add")){
				if(! in_array($extension_upload, $extensions_valid)){
					$alert= "Extension (".$extension_upload.") du fichier n'est pas admise.";
				}
			}
			// verification des parametres
			if ($label && $label != null && $label != "" 
				&& $ref && $ref!=null && $ref!=""
				 && $agriculture && $agriculture!= null && $agriculture!=""
				  && $fk_supplier && $fk_supplier != null && $fk_supplier != ""
				   && $packaging && $packaging != null && $packaging != ""
					&& $price && $price != null && $price != ""
					 && $weight_unit && $weight_unit != null && $weight_unit != ""
					  && $description && $description != null && $description != ""
					   && ($visibility == 1 || $visibility == 0)
						&& ($availability == 1 || $availability == 0)
						/*&& !$alert*/){
				
				$product = new Product (array(
					"label"=>$label,
					"ref"=>$ref,
					"agriculture"=>$agriculture,
					"fk_supplier"=>(int) $fk_supplier,
					"fk_cat"=>(int) $fk_category,
					"packaging"=>$packaging,
					"package_weight"=>$package_weight,
					"weight_unit"=>$weight_unit,
					"price"=>(float) $price,
					"supplier_price"=>(float) $supplier_price,
					"description"	=> $description,
					"availability"	=> $availability,
					"visibility"=> $visibility));

				if($mode=="add") $product = $this->dbManager->create($product);
				elseif($mode=="edit"){
					$product->setId((int)$_REQUEST['id_product']);
					$product = $this->dbManager->update($product);
				}
				if($product){
					// New product created successfully
					if($mode == 'add' || ($_FILES['picture']['error'] == 0 && $mode=="edit")){
						$filename= "files/pictures/product/".$product->id().".".$extension_upload;
						$movefile= move_uploaded_file($_FILES['picture']['tmp_name'], $filename);
						if($movefile) $alert= "Enregistrement effectué.";
						else $alert="Produit sauvegardé mais une erreur est survenue lors de l'enregistrement de l'image du produit";
					}
					else $alert= "Enregistrement effectué.";
				}
				else $alert="Parametre(s) invalide(s) pour la base de donnée";
			}
			else $alert="Valeurs des parametre(s) manquant(s) ou invalide(s)";
		}
		else $alert="Formulaire incomplet";
		$products= $this->dbManager->loadProducts();
		return parent::render("admin/list/product.html", array(
											"products"=>$products,
											"alert"=>$alert
									));
	}
	/**
	* @Route("/post/supplier")
	* @Method("POST")
	*/
	public function postSupplierAction(){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$label=NULL;
		$code=NULL;
		$phone=NULL;
		$email=NULL;
		$address=NULL;
		$description=NULL;
		$zip=NULL;
		$town=NULL;
		$department=NULL;
		$mode=NULL;
		if(array_key_exists('label', $_REQUEST)
			&& array_key_exists('code', $_REQUEST)
			 && array_key_exists('phone', $_REQUEST)
			  && array_key_exists('email', $_REQUEST)
			   && array_key_exists('address', $_REQUEST)
				&& array_key_exists('description', $_REQUEST)
				 && array_key_exists('zip', $_REQUEST)
				  && array_key_exists('town', $_REQUEST)
				   && array_key_exists('department', $_REQUEST)
					&& array_key_exists('mode', $_REQUEST)){
			$label = (!empty($_REQUEST['label'])) 
				? htmlentities($_REQUEST['label'])
				: false;
			$code = (!empty($_REQUEST['code']))
				? htmlentities($_REQUEST['code'])
				: false;
			$phone = (!empty($_REQUEST['phone']))
				? htmlentities($_REQUEST['phone'])
				: false;
			$email = (!empty($_REQUEST['email']))
				? htmlentities($_REQUEST['email'])
				: false;
			$address = (!empty($_REQUEST['address']))
				? htmlentities($_REQUEST['address'])
				: false;
			$zip = (!empty($_REQUEST['zip']))
				? htmlentities($_REQUEST['zip'])
				: false;
			$town = (!empty($_REQUEST['town']))
				? htmlentities($_REQUEST['town'])
				: false;
			$department = (!empty($_REQUEST['department']))
				? htmlentities($_REQUEST['department'])
				: false;
			$description = (!empty($_REQUEST['description']))
				? htmlentities($_REQUEST['description'])
				: false;
			$mode=$_REQUEST['mode'];
		// verification des parametres
			if ($label && $label != null && $label != "" 
				&& $code && $code != null && $code != ""
				 && $zip && $zip !=null && $zip != ""
				  && $town && $town != null && $town != ""
				   && $department && $department!=null && $department!=""
					&&$description && $description!=null && $description!=""){
				$supplier = new Supplier (array(
					"label"=>$label,
					"code"=>$code,
					"phone"=>$phone,
					"email"=>$email,
					"address"=>$address,
					"zip"=>$zip,
					"town"=>$town,
					"department"=>$department,
					"description"=>$description));
				if($mode == "add") $supplier = $this->dbManager->create($supplier);
				elseif($mode="edit"){
					$supplier->setId((int)$_REQUEST['id_supplier']);
					$supplier = $this->dbManager->update($supplier);
				}
				if($supplier){
					$alert= ( $mode == "add")
						? "Producteur enregistré."
						: "Producteur modifié.";
				}
				else $alert= "Erreur DAO! Réponse DB NULL";
			}
			else $alert= "Erreur valeur des données";	
		}
		else $alert="Formulaire incomplet";
		return parent::render("admin/list/supplier.html", array(
											"alert"=>$alert
									));
	}
	/**
	* @Route("/post/supplierContact/:supplierID")
	* @Method("POST")
	*/
	public function postSupplierContactAction($supplierID){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$fk_supplier= (int) $supplierID;
		$gender=NULL;
		$lastname=NULL;
		$name=NULL;
		$address=NULL;
		$zip=NULL;
		$town=NULL;
		$phone=NULL;
		$email=NULL;
		$mode=NULL;
		if(array_key_exists('gender', $_REQUEST)
			&& (array_key_exists('lastname', $_REQUEST)
			 || array_key_exists('name', $_REQUEST)
			  || array_key_exists('phone', $_REQUEST)
			   || array_key_exists('email', $_REQUEST))){
			$gender = (!empty($_REQUEST['gender']))
				? htmlentities($_REQUEST['gender'])
				: false;
			$lastname = (!empty($_REQUEST['lastname']))
				? htmlentities($_REQUEST['lastname'])
				: false;
			$name = (!empty($_REQUEST['name']))
				? htmlentities($_REQUEST['name'])
				: false;
			$address = (!empty($_REQUEST['address']))
				? htmlentities($_REQUEST['address'])
				: false;
			$zip = (!empty($_REQUEST['zip']))
				? htmlentities($_REQUEST['zip'])
				: false;
			$town = (!empty($_REQUEST['town']))
				? htmlentities($_REQUEST['town'])
				: false;
			$phone = (!empty($_REQUEST['phone']))
				? htmlentities($_REQUEST['phone'])
				: false;
			$email = (!empty($_REQUEST['email']))
				? htmlentities($_REQUEST['email'])
				: false;
			$mode= $_POST["mode"];
			$supplierContact = new SupplierContact (array(
				"fk_supplier"=>$fk_supplier,
				"gender"=>$gender,
				"lastname"=>$lastname,
				"name"=>$name,
				"address"=>$address,
				"zip"=>$zip,
				"town"=>$town,
				"email"=>$email,
				"phone"=>$phone));
			if($mode=="add") $supplierContact = $this->dbManager->createClassContact($supplierContact);
			elseif($mode=="edit"){
				$supplierContact->setId((int)$_POST["id_supplierContact"]);
				$supplierContact= $this->dbManager->updateClassContact($supplierContact);
			}
			if($supplierContact){
				$alert= ($mode=="add")
					?	"Enregistrement effectué."
					:	"Modifications effectuées.";
			}
			else $alert= "Erreur Inconnue. TRAITEMENT OU REQUETE DB";
		}
		else $alert="Parametre(s) manquant(s)";
		$suppliers= $this->dbManager->loadSuppliers();
		return parent::render("admin/list/supplier.html", array(						//OR $this->adminSupplierAction($alert);
										"alert"=> $alert,
										"suppliers"=> $suppliers));
	}
	/**
	* @Route("/del/:object/:id")
	* @Method("GET")
	*/
	public function deleteFormConfirmAction(string $object, int $id){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$target= array(
			"type"=>$object,
			"id_object"=>$id);
		return parent::render("admin/admin_delConfirm.html", array(
			"object"=> $target));
	}
	/**
	* @Route("/del/:object/:id")
	* @Method("POST")
	*/
	public function deleteObjectAction(string $object, int $targetID){
		if($this->session["status"] != "admin") return parent::render("admin/form/connect.html");
		$loadTarget= "load".ucfirst($object);
		$target= $this->dbManager->$loadTarget($targetID);
		if($target){
			$result= $this->dbManager->remove($target);
			return parent::render("admin/admin_success.html");
		}
	}
	/**
	* @Route("/pdfGenerator/:ref")
	* @Method("GET")
	*/
	public function pdfGeneratorAction($ref){
		$order=	(htmlentities($ref) != null) 
			?	$this->dbManager->loadOrder($ref)
			:	false;
		if(!$order){
			return parent::render("error/400.html", array("message" => "Commande introuvable"));
		}
	// Instanciation de la classe dérivée
		$pdf = new billingPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->orderDetail($order);
		$pdf->orderlinesDetails($order);
		$pdf->Output();
	}
}
