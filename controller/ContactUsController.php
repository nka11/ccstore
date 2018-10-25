<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class ContactUsController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    return parent::render('contactUs/form.html');
  }
  /**
   * @Route("/sendMail")
   * @Method("POST")
   */
  function sendMailAction(){
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
			$alert = "L'email fourni n'est pas valide";
		}
		else{
			$headers = ""; // we clear the variable
			$headers = "From: $lastname $name <$email>\n"; // Adding the From field
			// $headers = $headers."MIME-Version: 1.0\n"; // Adding the MIME version
			$headers = $headers."Content-type: text/plain; charset=iso-8859-1\n"; // Add the type of encoding
			$mail_sent= mail("contact@courtcircuit.bio", $subject, html_entity_decode($message), $headers);
			if($mail_sent) return parent::render('contactUs/sending_succeed.html');
			else $alert=" Une erreur est survenue lors de l'envoie de l'email.";
		}
	}
	else{
		$alert = "Formulaire incomplet";
	}
	return parent::render("contactUs/form.html", array("alert"=>$alert));
  }
}