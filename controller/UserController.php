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
    return parent::render('user.html', array("customer" => $_SESSION['user']) );
  }
  /**
   * Login method
   *
   * @Route("/login")
   */
  function loginAction() {
    $email = null;
    $password = null;
    // Parse headers to populate login vars
    if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
      && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
      $loginRequest = json_decode(stream_get_contents(STDIN));
      $email = $loginRequest->email;
      $password = $loginRequest->password;
    }
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
        if ($customer && $customer->api_key() != null && $customer->api_key() != "") {
          // Login success
          $_SESSION['statut'] = 'customer';
          $_SESSION['user'] = $customer;
          if ($this->isJson) {
            return '{"success": true}';
          }
          if (array_key_exists('forward',$_REQUEST)) {
            return header('Location: '.$_REQUEST['forward']);
          }
          return header('Location: '.$this->base_path);
        } else {
          // Login failure
          $message = "Email ou mot de passe invalide";
          if ($this->isJson) {
            return '{"success": false, "error": '+
              '{ "message" : "'.$message.'"}}';
          }
          http_response_code(403); //Unauthorized
          return parent::render("error/403.html", array("message"=> $message));
        }
      } catch (Throwable $t) {
        $message = "Email ou mot de passe invalide !";
        if ($this->isJson) {
          return '{"success": false, "error": '+
            '{ "message" : "'.$message.'"}}';
        } else {
          http_response_code(403); //bad request
          return parent::render("error/403.html", array("message"=> $message));
        }
      }

    } else { // Erreur de parametres
      $message = "Parametre email ou mot de passe manquant";
      if ($this->isJson) {
        return '{"success": false, "error": '+
          '{ "message" : "'.$message.'"}}';
      } else {
        http_response_code(400); //bad request
        return parent::render("error/400.html", array("message"=> $message));
      }
    }
  }

  /**
   * @Route("/logout")
   */
  function logoutAction () {
    session_destroy();
    if ($this->isJson) {
      return '{"success": true}';
    }
    if (array_key_exists('forward',$_REQUEST)) {
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
		 $firstname = null;
		 $address = null;
		 $zip = null;
		 $town = null;
		 $phone = null;
		 if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
		  && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
		  $postRequest = json_decode(stream_get_contents(STDIN));
		  $email = $postRequest->email;
		  $password = $postRequest->password;
		  $pwconfirm = $postRequest->pwconfirm;
		  $name = $postRequest->name;
		  $firstname = $postRequest->firstname;
		  $address = $postRequest->address;
		  $zip = $postRequest->zip;
		  $town = $postRequest->town;
		  $phone =  $postRequest->phone;
		}
		if(array_key_exists('email', $_REQUEST)
			&& array_key_exists('password', $_REQUEST)
			&& array_key_exists('pwconfirm', $_REQUEST)
			&& array_key_exists('name', $_REQUEST)
			&& array_key_exists('firstname', $_REQUEST)
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
			$firstname = (!empty($_REQUEST['firstname']))
				? htmlentities($_REQUEST['firstname'])
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
		 && $firstname && $firstname != null && $firstname != ""
		 && $address && $address !=  null && $address != ""
		 && $zip && $zip != null && $zip != ""
		 && $town && $town != null && $town != ""
		 && $phone && $phone != null && $phone != "") {
			// Confirmation du mot de passe
			if($password != $pwconfirm){  // No match password VS pwconfirm
				  $message = "Votre mot de passe n'est pas confirmé.";
				  if ($this->isJson) {
					return '{"success": false, "error": '+
					  '{ "message" : "'.$message.'"}}';
				  } else {
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				  }
			} 
			// Validation de l'email
			elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Email non valide
				$message = "L'email fourni n'est pas valide";
				  if ($this->isJson) {
					return '{"success": false, "error": '+
					  '{ "message" : "'.$message.'"}}';
				  } else {
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				  }
			}
			// Validation du code postal (zip)
			elseif( filter_var($zip, FILTER_VALIDATE_REGEXP,	array("options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
				$message = "Le code postal fourni n'est pas valide";
				  if ($this->isJson) {
					return '{"success": false, "error": '+
					  '{ "message" : "'.$message.'"}}';
				  } else {
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				  }
			}
			// Validation du telephone
			elseif( filter_var($phone, FILTER_VALIDATE_REGEXP, array("options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
				$message = "Le numero de téléphone fourni n'est pas valide";
				  if ($this->isJson) {
					return '{"success": false, "error": '+
					  '{ "message" : "'.$message.'"}}';
				  } else {
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				  }
			}
			else { // All param OK
				$customer = new Customer( array(
					"id_c" => null,
					"email" => $email,
					"password" => $password,
					"name" => $name,
					"firstname" => $firstname,
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
					if ($this->isJson) {
						return '{"success": false, "error": '+
							'{ "message" : "'.$message.'"}}';
					} else {
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $t));
					}
				}
				if($customer) {
						// New customer created successfully
						/**
						 *
						 * Send mail to customer->email to confirm that email exist.
						 */
					 return parent::render("inscription_success.html",  array("customer"=>$customer));
				}
				else {
					// Registration failure
					$message = "L'addresse email fournie existe déjà";
					if ($this->isJson) {
						return '{"success": false, "error": '+
							'{ "message" : "'.$message.'"}}';
					} else {
						http_response_code(400); //bad request
						return parent::render("error/400.html", array("message"=> $message));
					}
				}
			}
		} else{ 
			// Erreur de parametres
			$message = "Un ou plusieurs parametre(s) manquant(s)";
			if ($this->isJson) {
				return '{"success": false, "error": '+
				  '{ "message" : "'.$message.'"}}';
			 } else {
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
			}
		}
	} // end postAction
}
