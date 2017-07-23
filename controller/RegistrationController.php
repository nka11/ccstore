<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class RegistrationController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $products = $this->loadProducts();
    return parent::render('registration/registration.html', array("products" => $products));
  }
  
  /**
	 * Post method
	 * 
	 * @Route("/post")
	 * @Method("POST")
	 */
	function postAction(){		// registration form reception and treatment
		 $label= null;
		 $email= null;
		 $password = null;
		 $pwconfirm = null;
		 $name = null;
		 $lastname = null;
		 $address = null;
		 $zip = null;
		 $town = null;
		 $phone = null;
		// check particular @param
		if(array_key_exists('label', $_POST)){
			$label= (!empty($_POST['label']))
				?	htmlentities($_POST['label'])
				:	false;
		}
		elseif ( array_key_exists('name', $_POST)
					&& array_key_exists('lastname', $_POST)){
			$name = (!empty($_POST['name']))
				? htmlentities($_POST['name'])
				: false;
			$lastname = (!empty($_POST['lastname']))
				? htmlentities($_POST['lastname'])
				: false;
		}
		else{
			// Erreur de parametres
			$message = "Label ou contact manquant";
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
		}
		// check common @param
		if(array_key_exists('email', $_POST)
			&& array_key_exists('password', $_POST)
			&& array_key_exists('pwconfirm', $_POST)
			&& array_key_exists('address', $_POST)
			&& array_key_exists('zip', $_POST)
			&& array_key_exists('town', $_POST)
			&& array_key_exists('phone', $_POST)){
			$email = (!empty($_POST['email'])) 
				? filter_var(htmlentities($_POST['email']), FILTER_SANITIZE_EMAIL)
				: false;
			$password = (!empty($_POST['password'])) 
				? htmlentities($_POST['password'])
				: false;
			$pwconfirm = (!empty($_POST['pwconfirm']))
				? htmlentities($_POST['pwconfirm'])
				: false;
			$address = (!empty($_POST['address']))
				? $_POST['address']
				: false;
			$zip = (!empty($_POST['zip']))
				? $_POST['zip']
				: false;
			$town = (!empty($_POST['town']))
				? $_POST['town']
				: false;
			$phone = (!empty($_POST['phone']))
				? htmlentities($_POST['phone'])
				: false;
		}
		// @param validation
		if ($email && $email != null && $email != "" 
		 && $password && $password != null && $password != ""
		 && $pwconfirm && $pwconfirm != null && $pwconfirm != ""
		 && (($name && $name != null && $name != ""
		 && $lastname && $lastname != null && $lastname != "")
		  || ($label && $label !=null && $label != ""))
		 && $address && $address !=  null && $address != ""
		 && $zip && $zip != null && $zip != ""
		 && $town && $town != null && $town != ""
		 && $phone && $phone != null && $phone != "") {
			// pw confirmation
			if($password != $pwconfirm){  // No match password VS pwconfirm
				  $message = "Votre mot de passe n'est pas confirmé.";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			} 
			// email validation
			elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Email non valide
				$message = "L'email fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			// zip validation
			elseif( filter_var($zip, FILTER_VALIDATE_REGEXP,	array(
				"options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
					$message = "Le code postal fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			// phone number validation
			elseif( filter_var($phone, FILTER_VALIDATE_REGEXP, array(
				"options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
					$message = "Le numero de téléphone fourni n'est pas valide";
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
			}
			else { // All param OK
				$customer = new Customer( array(
					"id" => null,
					"label" => $label,
					"email" => $email,
					"password" => password_hash($password, PASSWORD_DEFAULT),
					"name" => $name,
					"lastname" => $lastname,
					"address" => $address,
					"zip" => $zip,
					"town" => $town,
					"phone" => $phone,
					"email_code" => $this->createPw()
				));
				try{
				$customer = $this->custdao->createCustomer($customer);
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
					$this->sendMailTo($customer);
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
	function createPw() {
		// chaine de caractères qui sera mis dans le désordre:
		$str = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // 62 caractères au total
		$str = str_shuffle($str);
		$str = substr($str,0,8);
		return $str;
	}
	public function sendMailTo(Customer $customer){
		
		$www= "http://www.courtcircuit.bio/user/validation/".$customer->id()."/".$customer->email_code();
		$to= $customer->email();
		$subject= "[Court Circuit] Création de votre compte";
		$message= "Bonjour,\n
					Votre compte a bien été enregistré. Merci de valider votre adresse mail en cliquant sur le lien suivant :\n
					".$www."\n
					Un grand merci pour votre confiance.\n
					Cordialement,\n
					L'équipe de Court-Circuit Sénart\n\n
					Ce mail a été généré automatiquement, merci de ne pas y répondre.";
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers = ""; // we clear the variable
		$headers = "From: Ne_pas_répondre <noreply@courtcircuit.bio>\n"; // Adding the From field
		// $headers = $headers."MIME-Version: 1.0\n"; // Adding the MIME version
		$headers = $headers."Content-type: text/plain; charset=iso-8859-1\n"; // Add the type of encoding
		/*
		$headers .= "Content-type: text/plain; charset=iso-8859-1" . "\r\n";
		$headers .= ($customer->label()!= null)
				?	"To: ".$customer->label()." <".$customer->email().">" . "\r\n"
				:	"To: ".$customer->lastname()." ".$customer->name()." <".$customer->email().">" . "\r\n";
		$headers .= "From: [Court Circuit] Ne_pas_répondre <noreply@courtcircuit.bio>" . "\r\n";*/

		$mail= mail($to, $subject, $message, $headers);
	}
}