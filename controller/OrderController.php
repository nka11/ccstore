<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProductDAO.php';
require_once './model/CategoryDAO.php';
require_once './model/OrderDAO.php';
require_once './model/OrderLineDAO.php';

class OrderController extends AbstractController {
	/**
	 * @Route("/ini")
     * @Method("GET")
     */
   public function iniAction(){
	   if(!isset($this->session['user'])){
		   return parent::render('alert_connexion_required.html');
	   }
	   else{
		   //Load user
		   $user= $this->session['user'];
			// Check if an order is already pending for this customer
		   $order= $this->odao->getOrdersByCustomer($user, 'Pending');
		   if($order) {
			   return parent::render("order_recap.html",  array("order"=>$order)); 	// If order already pending, go to step 2.
		   }
		   else return parent::render('step1_orderform.html'); 									// If order do not pending, go to step 1.
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
		else return parent::render('alert_connexion_required.html');
		if(is_array($order)){
			// An error has occured...
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message" => $order["error"]));
		}
		else{
			 return parent::render("order_recap.html",  array("order"=>$order)); 
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
				$order= $this->odao->getOrderByRef($ref);
				if($order){
					foreach($this->session['basket'] as $article){
						$orderline= new OrderLine( array(
															"fk_order"=>$order->id(),
															"fk_product"=>$article['product']->id(),
															"amount"=>$article['amount'],
															"value"=>$article['value']
															));
						$ol= $this->oldao->createOrderLine($orderline);
						// If Customer purchase adhesion, change his status to "member"
							if($article['product']->label() == "Adhésion"){
							$user->setRank("member");
							$user= $this->custdao->updateCustomer($user);
							if($user) $_SESSION['user'] = $user;
							$this->handle_session();
						}
					}
					if($ol){
						$_SESSION['basket']=array();
						unset($this->session['basket']);
						$order->setStatus("Confirmed");
						$order= $this->odao->updateOrder($order);
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
				$order= $this->odao->getOrderByRef($ref);
				if($order){
					$order= $this->odao->deleteOrder($order);
					foreach( $_SESSION['basket'] as $key=>$article){
						if($article['product']->label()== "Adhésion") $target_key= $key;
					}
					if($target_key) unset($_SESSION['basket'][$target_key]);
					$this->handle_session();
					return parent::render("index.html");
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
				$order= $this->odao->getOrderByRef($ref);
				if($order){
					$change_status= ( $order->status() != $status)
								?	true
								:	false;
					if($change_status){
						$order->setStatus($status);
						$order= $this->odao->updateOrder($order);
						$orders= $this->loadOrders();
						return parent::render("admin/admin_list_order.html", array(
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
		$delivery_address=NULL;
		$delivery_zip=NULL;
		$delivery_town=NULL;
		$delivery_instructions=NULL;
		$delivery_date= null;
		$delivery_cost=0;
		$adhesion= null;
		$status=NULL;
		if (array_key_exists("delivery_address", $_POST)
			&& array_key_exists("delivery_town", $_POST)
			 && array_key_exists("delivery_instructions", $_POST)
				&& array_key_exists("delivery_date", $_POST)){
			// generate a ref for this order
			$ref= date("YdmB").rand(100, 999);
			// Check if customer is member...
			if($user->rank() != "member"){
				if(array_key_exists("adhesion", $_POST)){					// customer add adhesion to basket.
					//load product "adhesion"
					$adhesion= $this->pdao->getProductByLabel(htmlentities("Adhésion"));
					if($adhesion){																// Create new article in basket.
						$article= array(
							"amount"=>1,
							"product"=>$adhesion,
							"value"=>$adhesion->price()*1);
						$_SESSION['basket'][]= $article;
						$this->handle_session();
					}
					$delivery_cost= 0;														// define delivery cost with adhesion
				}
				else $delivery_cost= 5;													// define delivery cost without adhesion
			}
			$total_amount= $this->session['basket_amount'] + $delivery_cost;
			$fk_customer=$user->id();
			$delivery_address= (!empty($_POST['delivery_address']))
				? htmlentities($_POST['delivery_address'])
				: null;
			$delivery_zip=(!empty($_POST['delivery_zip']))
				? htmlentities($_POST['delivery_zip'])
				: null;
			$delivery_town=(!empty($_POST['delivery_town']))
				? htmlentities($_POST['delivery_town'])
				: null;
			$delivery_instructions=(!empty($_POST['delivery_instructions']))
				? htmlentities($_POST['delivery_instructions'])
				: null;
			$delivery_date= (!empty($_POST['delivery_date']))
				?	htmlentities($_POST['delivery_date'])
				: null;
			$status= "Pending";
			if( $delivery_address && $delivery_address != null && $delivery_address != ""
				&& $delivery_town && $delivery_town != null && $delivery_town != ""
					&& $delivery_date && $delivery_date != null && $delivery_date != ""){
				$order= new Order( array(
					"ref"=> $ref,
					"total_amount"=>$total_amount,
					"fk_customer"=> (int) $fk_customer,
					"delivery_address"=> $delivery_address,
					"delivery_zip"		=>	$delivery_zip,
					"delivery_town"=> $delivery_town,
					"delivery_date"=>	$delivery_date,
					"delivery_cost"	=>	$delivery_cost,
					"delivery_instructions"=> $delivery_instructions,
					"status"=> $status));
				try{
					$order= $this->odao->createOrder($order);
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
		}else{
			$e="Requète formulaire incomplète";
			return array("Error"=> $e);
		}
	}
	
}