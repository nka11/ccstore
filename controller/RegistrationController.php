<?php
require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class RegistrationController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
	if($this->session['status'] == 'admin') return parent::render('registration/registration.html');
    else return parent::render('registration/registration.html');
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
		 $user = null;
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
					$alert = "Votre mot de passe n'est pas confirmé.";
			} 
			// email validation
			elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Email non valide
				$alert= "L'email fourni n'est pas valide";
			}
			// zip validation
			elseif( filter_var($zip, FILTER_VALIDATE_REGEXP,	array(
				"options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
					$alert = "Le code postal fourni n'est pas valide";
			}
			// phone number validation
			elseif( filter_var($phone, FILTER_VALIDATE_REGEXP, array(
				"options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
					$alert = "Le numero de téléphone fourni n'est pas valide";
			}
			else { // All param OK
				$user= new User( array(
					"id" => null,
					"email" => $email,
					"name" => $name,
					"lastname" => $lastname,
					"user_address" => array( "0"=> array(
													"label"=> "Domicile",
													"address"=> $address,
													"zip"=> $zip,
													"town"=> $town)
											),
					"phone" => $phone,
					"password" => password_hash($password, PASSWORD_DEFAULT),
					"email_code" => $this->createPw()					
				));
				// Try to create New User . If user already known, return user
				$user= $this->dbManager->create($user);

				if($user) {
						// New user created successfully
						/**
						 * Send mail to user->email to confirm that email exist.
						 */
					$this->sendNotification($user);
					$this->sendMailTo($user);
					$alert= "Votre inscription a bien été prise en compte. Pensez à valider votre adresse de messagerie. Merci de votre confiance!";
					return parent::render("user/connection.html");
				}
				else {
					// Registration failure
					$alert = "L'addresse email fournie existe déjà";
				}
			} // END ALL PARAM OK
		} else{ 
			// Erreur de parametres
			$alert = "Un ou plusieurs parametre(s) manquant(s)";
		}
		return parent::render("registration/registration.html", array(	"alert"=>$alert));
	} // end postAction method
	function createPw() {
		// chaine de caractères qui sera mis dans le désordre:
		$str = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // 62 caractères au total
		$str = str_shuffle($str);
		$str = substr($str,0,8);
		return $str;
	}
	public function sendMailTo(User $user){
		
		$www= "https://www.courtcircuit.bio/user/validation/".$user->id()."/".$user->email_code();
		$to= $user->email();
		$subject= "[Court Circuit] Création de votre compte";
		$message= "Bonjour,\n
					Votre compte a bien été enregistré. Merci de valider votre adresse mail en cliquant sur le lien suivant :\n
					".$www."\n
					Un grand merci pour votre confiance.\n
					Cordialement,\n
					L'équipe de Court-Circuit Sénart\n\n
					Ce mail a été généré automatiquement, merci de ne pas y répondre.";
		//$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers = ""; // we clear the variable
		$headers = "From: Ne_pas_répondre <noreply@courtcircuit.bio>\n"; // Adding the From field
		$headers = $headers."MIME-Version: 1.0\n"; // Adding the MIME version
		$headers = $headers."Content-type: text/plain; charset=iso-8859-1\n"; // Add the type of encoding
		/*
		$headers .= "Content-type: text/plain; charset=iso-8859-1" . "\r\n";
		$headers .= ($customer->label()!= null)
				?	"To: ".$customer->label()." <".$customer->email().">" . "\r\n"
				:	"To: ".$customer->lastname()." ".$customer->name()." <".$customer->email().">" . "\r\n";
		$headers .= "From: [Court Circuit] Ne_pas_répondre <noreply@courtcircuit.bio>" . "\r\n";*/

		$mail= mail($to, $subject, $message, $headers);
	}
	public function sendNotification(User $user){
		
		//$www= "http://www.courtcircuit.bio/user/validation/".$customer->id()."/".$customer->email_code();
		$to= "contact@courtcircuit.bio";
		$subject= "[Court Circuit] Nouvelle inscription";
		$message= "Nouvelle utilisateur! \n
					Un nouveau compte à bien été enregistré : \n
					Nom : ".ucfirst($user->lastname())." \n
					Prénom : ".ucfirst($user->name())." \n
					Mail : ".$user->email()." \n\n
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