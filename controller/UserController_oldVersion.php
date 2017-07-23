<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/CustomerDAO.php';
class UserController extends AbstractController {
  /**
   * Index /user/, shows user profile
   *
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
	$user= $this->init();
	if(!$user) return parent::render("alert_connexion_required.html"); // escape visitor session and invit for log in
	else{
		$orders= $this->odao->getOrdersByCustomer($user);
		foreach($orders as $order){
			$order = $this->hydrateOrder($order);
		}
		return parent::render('user/user.html', array(
													"customer" => $user,
													"orders"		=> $orders) );
	}
  }
  /**
   * Login method
   *
   * @Route("/login")
   */
  function loginAction() {
    $email = null;
    $password = null;
    if (array_key_exists('email', $_REQUEST) 
      && array_key_exists('password',$_REQUEST)) {
      $email = $_REQUEST['email'];
      $password = $_REQUEST['password'];
    }
    // verification des parametres
    if ($email != null && $email != "" 
      && $password != null && $password != "") {
      $custdao = new CustomerDAO();
      try {
        $customer = $custdao->getCustomerByEmail($email);
        $customer->setPassword($password);
        $customer = $custdao->login($customer);
        if ($customer) {
			  // Login success
			  $_SESSION['status'] = 'customer';
			  $_SESSION['user'] = $customer;
			  if (array_key_exists('forward',$_REQUEST)) {
				return header('Location: '.$_REQUEST['forward']);
			  }
			  return header('Location: '.$this->base_path);
        } else {
          // Login failure
          $message = "Mot de passe invalide";
          http_response_code(403); //Unauthorized
          return parent::render("error/403.html", array("message"=> $message));
        }
      } catch (Throwable $t) {
			$message = "Email ou mot de passe invalide !";
			http_response_code(403); //bad request
			return parent::render("error/403.html", array("message"=> $message));
      }

    } else { // Erreur de parametres
      $message = "Parametre email ou mot de passe manquant";
        http_response_code(400); //bad request
        return parent::render("error/400.html", array("message"=> $message));
    }
  } //end method login

  /**
   * @Route("/logout")
   */
  function logoutAction () {
    session_destroy();
    if (array_key_exists('forward',$_REQUEST)) {
		if($_REQUEST['forward'] == '/ccstore/user/'){		// Do not stay on profile page after logout.
			return header('Location: '. $this->base_path);
		}
      return header('Location: '.$_REQUEST['forward']);
    }
    return header('Location: '.$this->base_path);
  }
  
  /**
	 * Post method
	 * 
	 * @Route("/post")
	 */
	function postAction(){
		 $email= null;
		 $password = null;
		 $pwconfirm = null;
		 $name = null;
		 $lastname = null;
		 $address = null;
		 $zip = null;
		 $town = null;
		 $phone = null;
		
		if(array_key_exists('email', $_REQUEST)
			&& array_key_exists('password', $_REQUEST)
			&& array_key_exists('pwconfirm', $_REQUEST)
			&& array_key_exists('name', $_REQUEST)
			&& array_key_exists('lastname', $_REQUEST)
			&& array_key_exists('address', $_REQUEST)
			&& array_key_exists('zip', $_REQUEST)
			&& array_key_exists('town', $_REQUEST)
			&& array_key_exists('phone', $_REQUEST)){
			$email = (!empty($_REQUEST['email'])) 
				? filter_var(htmlentities($_REQUEST['email']), FILTER_SANITIZE_EMAIL)
				: false;
			$password = (!empty($_REQUEST['password'])) 
				? htmlentities($_REQUEST['password'])
				: false;
			$pwconfirm = (!empty($_REQUEST['pwconfirm']))
				? htmlentities($_REQUEST['pwconfirm'])
				: false;
			$name = (!empty($_REQUEST['name']))
				? htmlentities($_REQUEST['name'])
				: false;
			$lastname = (!empty($_REQUEST['lastname']))
				? htmlentities($_REQUEST['lastname'])
				: false;
			$address = (!empty($_REQUEST['address']))
				? $_REQUEST['address']
				: false;
			$zip = (!empty($_REQUEST['zip']))
				? $_REQUEST['zip']
				: false;
			$town = (!empty($_REQUEST['town']))
				? $_REQUEST['town']
				: false;
			$phone = (!empty($_REQUEST['phone']))
				? htmlentities($_REQUEST['phone'])
				: false;
		}
		// verification des parametres
		if ($email && $email != null && $email != "" 
		 && $password && $password != null && $password != ""
		 && $pwconfirm && $pwconfirm != null && $pwconfirm != ""
		 && $name && $name != null && $name != ""
		 && $lastname && $lastname != null && $lastname != ""
		 && $address && $address !=  null && $address != ""
		 && $zip && $zip != null && $zip != ""
		 && $town && $town != null && $town != ""
		 && $phone && $phone != null && $phone != "") {
			// Confirmation du mot de passe
			if($password != $pwconfirm){  // No match password VS pwconfirm
				  $message = "Votre mot de passe n'est pas confirmé.";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			} 
			// Validation de l'email
			elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Email non valide
				$message = "L'email fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			// Validation du code postal (zip)
			elseif( filter_var($zip, FILTER_VALIDATE_REGEXP,	array(
				"options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
					$message = "Le code postal fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			// Validation du telephone
			elseif( filter_var($phone, FILTER_VALIDATE_REGEXP, array(
				"options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
					$message = "Le numero de téléphone fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			else { // All param OK
				$customer = new Customer( array(
					"id" => null,
					"email" => $email,
					"password" => password_hash($password, PASSWORD_DEFAULT),
					"name" => $name,
					"lastname" => $lastname,
					"address" => $address,
					"zip" => $zip,
					"town" => $town,
					"phone" => $phone
				));
				$custdao = new CustomerDAO();
				try{
				$customer = $custdao->createCustomer($customer); 
				}
				catch(Throwable $t){
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $t));
				}

				if($customer) {
						// New customer created successfully
						/**
						 * Send mail to customer->email to confirm that email exist.
						 */
					 return parent::render("inscription_success.html",  array("customer"=>$customer));
				}
				else {
					// Registration failure
					$message = "L'addresse email fournie existe déjà";	
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
			} // END ALL PARAM OK
		} else{ 
			// Erreur de parametres
			$message = "Un ou plusieurs parametre(s) manquant(s)";
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
		}
	} // end postAction method
	/**
	 * edit method
	 * @Route("/edit/:attribut")
	 */
	function editAction($attribut) {
			// edit user email
			if($attribut == 'email'){
				$newemail = null;
				$confirmemail = null;
				$password = null;
				 if(array_key_exists('newemail', $_REQUEST)
				  && array_key_exists('confirmemail', $_REQUEST)
				  && array_key_exists('password', $_REQUEST)){
					$newemail = (!empty($_REQUEST['newemail'])) 
						? filter_var(htmlentities($_REQUEST['newemail']), FILTER_SANITIZE_EMAIL)
						: false;
					$confirmemail = (!empty($_REQUEST['confirmemail'])) 
						? filter_var(htmlentities($_REQUEST['confirmemail']), FILTER_SANITIZE_EMAIL)
						: false;
					$password = (!empty($_REQUEST['password']))
						? htmlentities($_REQUEST['password'])
						: false;
				 }
				 // verification des parametres
				if ($newemail && $newemail != null && $newemail != ""
				 && $confirmemail && $confirmemail != null && $confirmemail != ""
				 && $password && $password != null && $password != ""){
					 // Confirmation du mot de passe
					 if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
						 $message = "Votre mot de passe n'est pas confirmé.";
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					} // endif password match
					 // Is email sent valid
					 elseif (filter_var($newemail, FILTER_VALIDATE_EMAIL) === false
					  && filter_var($confirmemail, FILTER_VALIDATE_EMAIL) === false) {  // Emails non valide
						$message = "L'email fourni n'est pas valide";
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					  } // endif valid emails
					// Match newemail VS confirm email
					elseif($newemail != $confirmemail){ // emails do not match
						$message = "Votre nouvel email n'est pas confirmé";
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					} //endif match email
					// Testing newemail VS user current email 
					elseif($newemail == $this->session['user']->email()) { // newemail match with current email
						$message = "Votre nouvel email doit être différent de votre adresse mail actuelle";
						 http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					} //endif testing newemail VS currentemail
					// ALL PARAM OK
					else{
						$customer = $this->session['user'];		
						$customer->setEmail($newemail);
						$custdao = new CustomerDAO();
						try{
							$customer = $custdao->updateCustomer($customer);
						}
						catch( Throwable $t){
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $t));
						}
							if($customer){
								// Customer updated successfully
								/**
								 *
								 * Send mail to customer->email to confirm that email exist.
								 */
								$this->session['user'] = $customer;
								return parent::render("user/user.html", array("customer"=> $this->session['user']));
							}
							else {
								// Registration failure
								$message = "Une erreur inconnue s'est produite";
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $message));
							}	
					} // end ALL PARAM OK
				 } // endif verif param
				// Param setEmail non valid
				else{
					 $message = "Les champs du formulaire doivent être renseignés";
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
				 }
			} // endif attribut == email
			// edit user address
			elseif($attribut == 'address'){
				$newaddress = null;
				$newzip = null;
				$newtown = null;
				$password = null;
				 if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
				 && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
						$editRequest = json_decode(stream_get_contents(STDIN));
						$newaddress = $editRequest->newaddress;
						$newzip = $editRequest->newzip;
						$newtown = $editRequest->newtown;
						$password = $editRequest->password;
				 }
				  if(array_key_exists('newaddress', $_REQUEST)
				  && array_key_exists('newzip', $_REQUEST)
				  && array_key_exists('newtown', $_REQUEST)
				  && array_key_exists('password', $_REQUEST)){
					$newaddress = (!empty($_REQUEST['newaddress']))
						? htmlentities($_REQUEST['newaddress'])
						: false;
					$newzip = (!empty($_REQUEST['newzip'])) 
						? htmlentities($_REQUEST['newzip'])
						: false;
					$newtown = (!empty($_REQUEST['newtown']))
						? htmlentities($_REQUEST['newtown'])
						: false;
					$password = (!empty($_REQUEST['password']))
						? htmlentities($_REQUEST['password'])
						: false;
				 } //endif array_key
				 // Check param
				 if ($newaddress && $newaddress != null && $newaddress != ""
				 && $newzip && $newzip != null && $newzip != ""
				 && $newtown && $newtown != null && $newtown != ""
				 && $password && $password != null && $password != ""){
					 // Password match
					if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
						 $message = "Votre mot de passe n'est pas confirmé.";
						  if ($this->isJson) {
							return '{"success": false, "error": '+
							  '{ "message" : "'.$message.'"}}';
						  } else {
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
						  }
					 } // endif password match
					 // valid zip
					 elseif( filter_var($newzip, FILTER_VALIDATE_REGEXP,
						array("options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
							$message = "Le code postal fourni n'est pas valide";
							  if ($this->isJson) {
								return '{"success": false, "error": '+
								  '{ "message" : "'.$message.'"}}';
							  } else {
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $message));
							  }
					} //endif valid zip
					// ALL PARAM OK
					else{
						$customer = $this->session['user'];		
						$customer->setAddress($newaddress);
						$customer->setZip($newzip);
						$customer->setTown($newtown);
						$custdao = new CustomerDAO();
						try{
							$customer = $custdao->updateCustomer($customer);
						}
						catch( Throwable $t){
							if ($this->isJson) {
								return '{"success": false, "error": '+
									'{ "message" : "'.$message.'"}}';
							} else {
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $t));
							}
						}
							if($customer){
								// Customer updated successfully
								/**
								 *
								 * Send mail to customer->email to confirm that email exist.
								 */
								$this->session['user'] = $customer;
								return parent::render("user/user.html", array("customer"=> $this->session['user']));
							}
							else {
								// Registration failure
								$message = "Une erreur inconnue s'est produite";
								if ($this->isJson) {
									return '{"success": false, "error": '+
										'{ "message" : "'.$message.'"}}';
								} else {
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $message));
								}
							}	
					} // end ALL PARAM OK
				 } // endif check param
				// Param setAddress non valid
				else{
					 $message = "Les champs du formulaire doivent être renseignés";
						if ($this->isJson) {
							return '{"success": false, "error": '+
								'{ "message" : "'.$message.'"}}';
						} else {
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
						}
				 } 
			} //endif edit address
			// attribut == phone
			elseif($attribut == 'phone'){
				$newphone = null;
				$confirmphone = null;
				$password = null;
				 if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
				 && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
						$editRequest = json_decode(stream_get_contents(STDIN));
						$newphone = $editRequest->newphone;
						$confirmphone = $editRequest->confirmphone;
						$password = $editRequest->password;
				 }
				  if(array_key_exists('newphone', $_REQUEST)
				  && array_key_exists('confirmphone', $_REQUEST)
				  && array_key_exists('password', $_REQUEST)){
					$newphone = (!empty($_REQUEST['newphone']))
						? htmlentities($_REQUEST['newphone'])
						: false;
					$confirmphone = (!empty($_REQUEST['confirmphone'])) 
						? htmlentities($_REQUEST['confirmphone'])
						: false;
					$password = (!empty($_REQUEST['password']))
						? htmlentities($_REQUEST['password'])
						: false;
				  }
				 // Check param
				 if ($newphone && $newphone != null && $newphone != ""
				  && $confirmphone && $confirmphone != null && $confirmphone != ""
				  && $password && $password != null && $password != ""){
					   // Password match
					 if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
						 $message = "Votre mot de passe n'est pas confirmé.";
						  if ($this->isJson) {
							return '{"success": false, "error": '+
							  '{ "message" : "'.$message.'"}}';
						  } else {
							http_response_code(403); // auth error
							return parent::render("error/400.html", array("message"=> $message));
						  }
					 } // endif password match
					 // Valid phone
					 elseif(filter_var($newphone, FILTER_VALIDATE_REGEXP, array(
						"options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
							$message = "Le numero de téléphone fourni n'est pas valide";
							  if ($this->isJson) {
								return '{"success": false, "error": '+
								  '{ "message" : "'.$message.'"}}';
							  } else {
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $message));
							  }
					} // endif valid phone
					// match phones
					elseif($newphone != $confirmphone){ // no match
						$message = "Votre nouveau numero n'est pas confirmé";
						  if ($this->isJson) {
							return '{"success": false, "error": '+
							  '{ "message" : "'.$message.'"}}';
						  } else {
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
						  }
					} // endif match phones
					// ALL PARAM OK
					else{
						$customer = $this->session['user'];		
						$customer->setPhone($newphone);
						$custdao = new CustomerDAO();
						try{
							$customer = $custdao->updateCustomer($customer);
						}
						catch( Throwable $t){
							if ($this->isJson) {
								return '{"success": false, "error": '+
									'{ "message" : "'.$message.'"}}';
							} else {
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $t));
							}
						}
							if($customer){
								// Customer updated successfully
								/**
								 *
								 * Send mail to customer->email to confirm that email exist.
								 */
								$this->session['user'] = $customer;
								return parent::render("user/user.html", array("customer"=> $this->session['user']));
							}
							else {
								// Registration failure
								$message = "Une erreur inconnue s'est produite";
								if ($this->isJson) {
									return '{"success": false, "error": '+
										'{ "message" : "'.$message.'"}}';
								} else {
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $message));
								}
							}	
					} // end ALL PARAM OK
				 }
				// Param setAddress non valid
				else{
					 $message = "Les champs du formulaire doivent être renseignés";
						if ($this->isJson) {
							return '{"success": false, "error": '+
								'{ "message" : "'.$message.'"}}';
						} else {
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
						}
				 } // endif check param
			} //endif edit phone
			// edit password
			elseif($attribut == 'password'){
				$currentpassword = null;
				$newpassword = null;
				$pwconfirm = null;
				 if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
				 && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
						$editRequest = json_decode(stream_get_contents(STDIN));
						$currentpassword = $editRequest->currentpassword;
						$newpassword = $editRequest->newpassword;
						$pwconfirm = $editRequest->pwconfirm;
				 }
				  if(array_key_exists('currentpassword', $_REQUEST)
				  && array_key_exists('newpassword', $_REQUEST)
				  && array_key_exists('pwconfirm', $_REQUEST)){
					$currentpassword = (!empty($_REQUEST['currentpassword']))
						? htmlentities($_REQUEST['currentpassword'])
						: false;
					$newpassword = (!empty($_REQUEST['newpassword']))
						? htmlentities($_REQUEST['newpassword'])
						: false;
					$pwconfirm = (!empty($_REQUEST['pwconfirm']))
						? htmlentities($_REQUEST['pwconfirm'])
						: false;
				  }
				 // Check param
				 if ($currentpassword && $currentpassword != null && $currentpassword != ""
				  && $newpassword && $newpassword != null && $newpassword != ""
				  && $pwconfirm && $pwconfirm != null && $pwconfirm != ""){
					   // Password match
					 if(!password_verify($currentpassword, $this->session['user']->password())){ // No match submitted password VS user's password
						 $message = "Mot de passe incorrect";
							http_response_code(403); //error auth
							return parent::render("error/400.html", array("message"=> $message));
						  }
					 } // endif password match
					 // match new VS confirm
					 elseif($newpassword != $pwconfirm) { //no match
							$message = "Mot de passe non confirmé";
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $message));
							  }
					} // endif match new VS confirm
					// ALL PARAM OK
					else{
						$customer = $this->session['user'];		
						$customer->setPhone($newphone);
						$custdao = new CustomerDAO();
						try{
							$customer = $custdao->updateCustomer($customer);
						}
						catch( Throwable $t){
							if ($this->isJson) {
								return '{"success": false, "error": '+
									'{ "message" : "'.$message.'"}}';
							} else {
								http_response_code(400); //bad request
								return parent::render("error/400.html", array("message"=> $t));
							}
						}
							if($customer){
								// Customer updated successfully
								/**
								 *
								 * Send mail to customer->email to confirm that email exist.
								 */
								$this->session['user'] = $customer;
								return parent::render("user/user.html", array("customer"=> $this->session['user']));
							}
							else {
								// Registration failure
								$message = "Une erreur inconnue s'est produite";
								if ($this->isJson) {
									return '{"success": false, "error": '+
										'{ "message" : "'.$message.'"}}';
								} else {
									http_response_code(400); //bad request
									return parent::render("error/400.html", array("message"=> $message));
								}
							}	
					} // end ALL PARAM OK
				 }
				// Param setPassword non valid
				else{
					 $message = "Les champs du formulaire doivent être renseignés";
						if ($this->isJson) {
							return '{"success": false, "error": '+
								'{ "message" : "'.$message.'"}}';
						} else {
							http_response_code(400); //bad request
							return parent::render("error/400.html", array("message"=> $message));
						}
				 } // endif check param
			} // endif edit password
			// attribut unknown
			else{
				$message = "Votre requète n'est pas valide";
					  if ($this->isJson) {
						return '{"success": false, "error": '+
						  '{ "message" : "'.$message.'"}}';
					  } else {
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					  }
			} // end att unknown
	} // end editAction method 
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
		$pdf = new orderPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->orderDetail($order);
		$pdf->orderlinesDetails($order);
		$pdf->Output();
	 }
	public function init(){
		$user= $this->session['user'];
		if(!$user) return false;
		else return $user;
	}
}
