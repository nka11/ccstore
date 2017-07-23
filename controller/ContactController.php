<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class ContactController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    return parent::render('contact/contact.html');
  }
  /**
   * @Route("/contactUs")
   * @Method("POST")
   */
  function contactUsAction(){
	$email=null;
	$lastname=null;
	$name= null;
	$subject=null;
	$message=null;

	if(array_key_exists("email", $_POST)
		&& array_key_exists("lastname", $_POST)
			&& array_key_exists("name", $_POST)
				&& array_key_exists("subject", $_POST)
					&& array_key_exists("message", $_POST)){
		$email = (!empty($_POST["email"]))
			?	htmlentities($_POST["email"])
			:	false;
		$lastname = (!empty($_POST["lastname"]))
			?	htmlentities($_POST["lastname"])
			:	false;
		$name = (!empty($_POST["name"]))
			?	htmlentities($_POST["name"])
			:	false;
		$subject = (!empty($_POST["subject"]))
			?	htmlentities($_POST["subject"])
			:	false;
		$message = (!empty($_POST["message"]))
			?	htmlentities($_POST["message"])
			:	false;
	}
	// check @param
	if( $email && $email != null && $email!= ""
		&& $lastname && $lastname != null && $lastname != ""
			&& $name && $name != null && $name != ""
				&& $subject && $subject != null && $subject != ""
					&& $message && $message != null && $message != ""){
		//check email
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { // Email non valide
			$message = "L'email fourni n'est pas valide";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
		}
		else{
			$headers = ""; // we clear the variable
			$headers = "From: $lastname $name <$email>\n"; // Adding the From field
			// $headers = $headers."MIME-Version: 1.0\n"; // Adding the MIME version
			$headers = $headers."Content-type: text/plain; charset=iso-8859-1\n"; // Add the type of encoding
			$mail_sent= mail("contact@courtcircuit.bio", $subject, $message, $headers);
		}
	}
	else{
		$message = "Formulaire incomplet";
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
	}
	if($mail_sent) return parent::render('contact/sending_succeed.html');
	else{
		http_response_code(400); //bad request
		return parent::render("error/400.html", array("message"=> "Erreur lors de l'envoie du l'email"));
	}
  }
}