<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProductDAO.php';
require_once './model/CategoryDAO.php';
require_once './model/OrderDAO.php';
require_once './model/OrderLineDAO.php';
require_once './model/PaymentDAO.php';

class OrderController extends AbstractController {
	/**
	 * @Route("/ini")
     * @Method("GET")
     */
   public function iniAction(){
	   if(!isset($this->session['user'])){
		   $alert= "Connectez vous pour continuer!";
		   return parent::render('user/connection.html', array("alert"=>$alert));
	   }
	   else{
		   //Load user
		   $user= $this->session['user'];
			// Check if an order is already pending for this user
		   $order= $this->dbManager->loadOrders($user, 'Pending');
		   if($order) {
			   return parent::render("order/recap.html",  array("order"=>$order)); 	// If order already pending, go to step 2.
		   }
		   else return parent::render("order/step1_orderform.html"); 					// If order do not pending, go to step 1.
	   }
   }
   /**
    * @Route("/create")
	* @Method("POST")
	*/
	public function createOrderAction(){
		$error= array();
		$order= null;
		//Load user
		$user= $this->session['user'];
		if($user) $order= $this->treatFormCreateOrder();
		else return parent::render('user/connection.html', array("alert"=>$alert));
		if(is_array($order)){
			// An error has occured...
			$alert= $order["Error"];
			return parent::render("order/step1_orderform.html", array("alert" => $alert));
		}
		else{
			 return parent::render("order/recap.html",  array("order"=>$order));
		}
	}
	/**
    * @Route("/confirm")
	* @Method("POST")
	*/
	public function confirmAction(){
		$user= $this->session['user'];
		$ref= null;
		if(array_key_exists("ref", $_POST)){
			$ref= (!empty($_POST['ref']))
					? htmlentities($_POST['ref'])
					: null;
			if($ref && $ref>0 && $ref!= null && $ref!=""){
				$order= $this->dbManager->loadOrder($ref);
				if($order){
					foreach($this->session['basket'] as $article){
						$orderline= new OrderLine( array(
															"fk_order"=>$order->id(),
															"fk_product"=>$article['product']->id(),
															"amount"=>$article['amount'],
															"value"=>$article['value']
															));
						$ol= $this->dbManager->create($orderline);
					}
					if($ol){
						$_SESSION['basket']=array();
						unset($this->session['basket']);
						$order->setStatus("Confirmed");
						$order= $this->dbManager->update($order);
						return parent::render("order_record_success.html");
					}
					else{
							$message="Erreur de traitement des articles";
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
					}
				}
				else{
					$message="Erreur de traitement de la commande";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
			}
			else{
					$message="Référence non valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
		}
		else{
			$message="Référence manquante";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
			}
	}
	/**
    * @Route("/cancel")
	* @Method("POST")
	*/
	public function cancelAction(){
		$ref= null;
		$target_key= null;
		if(array_key_exists("ref", $_POST)){
			$ref= (!empty($_POST['ref']))
					? htmlentities($_POST['ref'])
					: null;
			if($ref && $ref>0 && $ref!= null && $ref!=""){
				$order= $this->dbManager->loadOrder($ref);
				if($order){
					$order= $this->dbManager->remove($order);
					foreach( $_SESSION['basket'] as $key=>$article){
						if($article['product']->label()== "Adhésion") $target_key= $key;
					}
					if($target_key) unset($_SESSION['basket'][$target_key]);
					$this->handle_session();
					return parent::render("portal.html");
				}
				else{
					$message="Erreur de traitement";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
			}
			else{
					$message="Référence non valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
		}
		else{
			$message="Référence manquante";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
			}
	}
	/**
	 * @Route("/editStatus")
	 * @Method("POST")
	 */
	public function editStatusAction(){
		$status= null;
		$ref=null;
		if(array_key_exists("ref", $_POST)
			&& array_key_exists("status", $_POST)){
			$ref= (!empty($_POST['ref']))
					? htmlentities($_POST['ref'])
					: null;
			$status= (!empty($_POST['status']))
					? htmlentities($_POST['status'])
					: null;
			if($ref && $ref>0 && $ref!= null && $ref!=""
				&& $status && $status!=null && $status!= ""){
				$order= $this->dbManager->loadOrder($ref);
				if($order){
					$change_status= ( $order->status() != $status)
								?	true
								:	false;
					if($change_status){
						$order->setStatus($status);
						$order= $this->dbManager->update($order);
						$orders= $this->dbManager->loadOrders();
						return parent::render("admin/list/order.html", array(
							"orders" => $orders));
					}
				}
				else{
					$message="Commande introuvable";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
			}
			else{
				$message="Paramêtres non valides";
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
			}
		}
		else{
			$message="Paramêtre manquants ou non valides";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
		}
	}
	public function treatFormCreateOrder(){
		$user= $this->session['user'];
		$ref=NULL;
		$total_amount=NULL;
		$fk_customer=NULL;
		$delivery_address_label=NULL;
		$delivery_address=NULL;
		$delivery_zip=NULL;
		$delivery_town=NULL;
		$delivery_instructions=NULL;
		$delivery_date= null;
		$delivery_cost=0;
		$adhesion= null;
		$status=NULL;
		// ADDRESS
		if (array_key_exists("user_address_label", $_POST)){
			$address_label = (!empty( $_POST['user_address_label']))
									?	$_POST['user_address_label']
									:	FALSE;
			if($address_label){
				foreach($user->user_address() as $address){
					if($address_label == $address['label']){
						$selectedAddress = array(
										"label"=> $address['label'],
										"address"=> $address['address'],
										"zip"=> $address['zip'],
										"town"=> $address['town']
										);
					}
				}
			}
			elseif(array_key_exists("delivery_address", $_POST)
					&& array_key_exists("delivery_town", $_POST)
						&& array_key_exists("delivery_zip", $_POST)){
				$delivery_address= (!empty($_POST['delivery_address']))
					? htmlentities($_POST['delivery_address'])
					: null;
				$delivery_zip=(!empty($_POST['delivery_zip']))
					? htmlentities($_POST['delivery_zip'])
					: null;
				$delivery_town=(!empty($_POST['delivery_town']))
					? htmlentities($_POST['delivery_town'])
					: null;
				$selectedAddress = array(
									"label" =>	"Nouvelle adresse",
									"address" => $delivery_address,
									"zip"	=>	$delivery_zip,
									"town"	=>	$delivery_town
								);
			}
		}
		// DELIVERY_DATE AND DELIVERY_INSTRUCTIONS
		if(array_key_exists("delivery_instructions", $_POST)
			&& array_key_exists("delivery_date", $_POST)){
			$delivery_instructions=(!empty($_POST['delivery_instructions']))
				? htmlentities($_POST['delivery_instructions'])
				: null;
			$delivery_date= (!empty($_POST['delivery_date']))
				?	htmlentities($_POST['delivery_date'])
				: null;
		}
		// Add adhesion if required
		if(!$user->is_Member()){
			if(array_key_exists("adhesion", $_POST)){					// customer add adhesion to basket.
				//load product "adhesion"
				$adhesion= $this->dbManager->loadProduct(htmlentities("ADHECOCI"));
				if($adhesion){
					// Create new adhesion for dataBase
					$user= $this->dbManager->createUserAdhesion($user);
					$_SESSION['user'] = $user;
					// Create new article in basket.
					$article= array(
						"amount"=>1,
						"product"=>$adhesion,
						"value"=>$adhesion->price()*1);
					$_SESSION['basket'][]= $article;
					$this->handle_session();
				}
			}
		}
			// generate a ref for this order
			$ref= date("YdmB").rand(100, 999);
			// Check if customer is member...
			$fk_customer=$user->id();
			$status= "Pending";
			//delivery cost and total amount
			$delivery_cost= ($user->is_member())
						?	0
						:	5;
			$total_amount= $this->session['basket_amount'] + $delivery_cost;
			if( $selectedAddress && $selectedAddress != null && $selectedAddress != ""
					&& $delivery_date && $delivery_date != null && $delivery_date != ""){
				$order= new Order( array(
					"ref"=> $ref,
					"total_amount"=>$total_amount,
					"fk_customer"=> (int) $fk_customer,
					"customer_type"=> $this->session['status'],
					"delivery_address"=> $selectedAddress['address'],
					"delivery_zip"		=>	$selectedAddress['zip'],
					"delivery_town"=> $selectedAddress['town'],
					"delivery_date"=>	$delivery_date,
					"delivery_cost"	=>	$delivery_cost,
					"delivery_instructions"=> $delivery_instructions,
					"origin"=> "WEB",
					"status"=> $status));
				try{
					$order= $this->dbManager->create($order);
				}
				catch(Throwable $t){
					return array("Error"=> $t);
				}
				if($order){
					// Afficher le contenu de la commande pour confirmation
					return $order;
				}
				else{
					$e="Pas d'enregistrement en base";
					return array("Error"=> $e);
				}
			}
			else{ 
					$e="Parametre(s) manquant(s) ou invalide(s)";
					return array("Error"=> $e);
			}
	}
	
}