<?php
require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/class/orderPDF.class.php';
require_once './model/UserDAO.php';
require_once './model/ContactDAO.php';
class UserController extends AbstractController {
  /**
   * Index /user/, shows user profile
   *
   * @Route("/")
   * @Method("GET")
   */
   function indexAction() {
	$user= $this->session['user']; 
	if(!$user) return parent::render("user/connection.html"); // escape visitor session and invit for log in
	else{
		if(is_array($user->orders())){
			foreach($user->orders() as $order){
				if($order->delivery_week() == $this->orderblock['week']) $current_orders[]= $order;
			}
		}
		return parent::render('user/dashboard.html', array( "user"  => $user,
															"current_orders"	=> $current_orders));
	}
  }
  /**
   * Call Connection Page
   *
   * @Route("/connection")
   * @Method ("GET")
   */
   function connectionAction() {
		if(empty($_SESSION['user'])){
			return parent::render('user/connection.html');
		}
		else{
			return parent::render('user/dashboard.html');
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
    if (array_key_exists('email', $_POST) 
      && array_key_exists('password',$_POST)) {
      $email = $_POST['email'];
      $password = $_POST['password'];
    }
    // check param
    if ($email != null && $email != "" 
      && $password != null && $password != "") {
      $user = $this->dbManager->loadUserByEmail($email);
      if ($user) {
			if(password_verify($password, $user->password())){
				// Login success
				$_SESSION['status'] = 'user';
				$_SESSION['user'] = $user;
				if (array_key_exists('forward',$_REQUEST)) {
					if( $_REQUEST['forward'] == "/order/ini")
						return header('Location: '.$_REQUEST['forward']);
				}
				$this->handle_session();
				$alert= "Bonjour ".$user->name()."!";
				return parent::render("user/dashboard.html", array("user"=> $user,
																	"alert"=>$alert));
			}  
			else {
				// Login failure
				$alert = "Mot de passe invalide";
			}
		}
		else{
			$alert = "Email non valide";
		}
	} else { // Erreur de parametres
		$alert = "Parametre email ou mot de passe manquant";
    }
	return parent::render("user/connection.html", array(	"alert"=>$alert));
  } //end method login

  /**
   * @Route("/logout")
   * @Method("POST")
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
	 * User password forgotten
	 * @Route("/password/forgotten")
	 * @Method("GET")
	 */
	function pwForgottenAction(){
		return parent::render("user/password/forgotten.html");
	}
	/**
	 * User password rezet
	 * @Route("/password/rezet")
	 * @Method("POST")
	 */
	function pwRezetAction(){
		$email=null;
		$str=null;
		$user=null;
		$alert=null;
		if (array_key_exists('email', $_POST)){
			$email = $_POST['email'];
			// check param
			if ($email && $email != null && $email != ""){
				$user = $this->dbManager->loadUserByEmail($email);
				if($user){
					$str = "0123456789"; // 10 caractères au total
					$str = str_shuffle($str);
					$str = substr($str,0,6);
					$this->sendRequest($user, $str);
					$str = password_hash($str, PASSWORD_DEFAULT);
					$user->setTemp_code($str);
					$_SESSION['temp_request']= $user;
					
					return parent::render("user/password/rezet.html");
				}
				else{
					$alert= "Aucun compte utilisateur correspondant";
					return parent::render("user/password/forgotten.html", array(
																				"alert"	=>	$alert,
																				"str"	=>	$str));
				}
			}
			else{
				$alert= "Adresse email non valide";
			}
		}
		elseif(array_key_exists('str', $_POST)){
			$str = $_POST['str'];
			// check param
			if($str && $str!=null && $str !=""){
				if(password_verify($str, $_SESSION['temp_request']->temp_code())){
					// Handle session
					$user= $_SESSION['temp_request'];
					// generate new password
					$new= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$new = str_shuffle($new);
					$new = substr($new,0,8);
					$xnew = password_hash($new, PASSWORD_DEFAULT);
					// update user password
					$user->setPassword($xnew);
					$user= $this->dbManager->update($user);
					// Envoyer un mail avec nouveau mot de passe.
					if($user){
						$this->sendMail($user, $new);					
						$alert= "Authentification réussie : Votre mot de passe à été réinitialisé. Un mail vous a été adressé.";
					}
					else $alert= "Une erreur inconnue s'est produite. Veuillez réessayer."; 
					return parent::render("user/connection.html", array("alert" => $alert));
				}
				else{
					$alert= "Authentification échouée";
					return parent::render("user/connection.html", array("alert" => $alert));
				}
			}
		}
	}
	/**
	 * new user email validation
	 * @Route("/validation/:id/:code")
	 * @Method("GET")
	 */
	function validationAction($id, $code){
		$user = ( htmlentities($id) != null)
				?	$this->dbManager->loadUser((int)$id)
				:	false;
		if($user) {
			//check email_code
			if($user->email_code() != "is_valid"){ // escape email already verified
				if( $user->email_code() == $code){
					$user->setEmail_code("is_valid");
					$user= $this->dbManager->update($user);
					return parent::render("email_validation_success.html");
				}
				else{
					$message = "Code de validation erroné";	
					http_response_code(400); //bad request
					return parent::render("error/400.html", array("message"=> $message));
				}
			}
			else{
				$message = "Adresse email déjà vérifiée";	
				http_response_code(400); //bad request
				return parent::render("error/400.html", array("message"=> $message));
			}
		}else{
			$message = "L'utilisateur n'existe pas";	
			http_response_code(400); //bad request
			return parent::render("error/400.html", array("message"=> $message));
		}
	}
	/**
	 * edit method
	 * @Route("/edit/:attribut")
	 * @Method("POST")
	 */
	function editAction($attribut) {
		$fn= "treatFormUser_".$attribut;
		$user= $this->$fn();
		if(is_array($user)){
			// an error has occured
			$message = $user['error'];
		}
		elseif($user){
			// treatment done successfully
			$user = $this->dbManager->update($user);
			$message = ($user)
						?	"Modifications enregistrées"
						:	"Erreur de traitement";
			$_SESSION['user'] = $user;
			$this->handle_session();
		}
		return parent::render("/user/user.html", array("message"=>$message));
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
		$pdf = new orderPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->orderDetail($order);
		$pdf->orderlinesDetails($order);
		$pdf->Output();
	 }
	public function treatFormUser_address(){
		$newaddress = null;
		$newzip = null;
		$newtown = null;
		$password = null;
		if(array_key_exists('newaddress', $_POST)
			&& array_key_exists('newzip', $_POST)
				&& array_key_exists('newtown', $_POST)
					&& array_key_exists('password', $_POST)){
			$newaddress = (!empty($_POST['newaddress']))
				? htmlentities($_POST['newaddress'])
				: false;
			$newzip = (!empty($_POST['newzip'])) 
				? htmlentities($_POST['newzip'])
				: false;
			$newtown = (!empty($_POST['newtown']))
				? htmlentities($_POST['newtown'])
				: false;
			$password = (!empty($_POST['password']))
				? htmlentities($_POST['password'])
				: false;
		 } //endif array_key
		 // Check param
		 if ($newaddress && $newaddress != null && $newaddress != ""
			&& $newzip && $newzip != null && $newzip != ""
				&& $newtown && $newtown != null && $newtown != ""
					&& $password && $password != null && $password != ""){
			 // Password match
			if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
				 $message["error"] = "Mot de passe incorrecte";
				 return $message["error"];
			 }
			 // valid zip
			 elseif( filter_var($newzip, FILTER_VALIDATE_REGEXP,
				array("options"=>array("regexp"=>"#[0-9]{5}$#"))) === false){
					$message["error"] = "Le code postal fourni n'est pas valide";
					return $message["error"];
			}
			else{
				// ALL PARAM OK
				$user = $this->session['user'];
				$user->setAddress($newaddress);
				$user->setZip($newzip);
				$user->setTown($newtown);
				return $user;
			}
		}
		else{
			$message["error"]= "Paramêtres non valides";
			return $message;
		}
	}
	public function treatFormUser_email(){
		$newemail = null;
		$confirmemail = null;
		$password = null;
		 if(array_key_exists('newemail', $_POST)
			&& array_key_exists('confirmemail', $_POST)
				&& array_key_exists('password', $_POST)){
			if(!empty($_POST['newemail'])){
				$newemail = (filter_var(htmlentities($_POST['newemail']), FILTER_SANITIZE_EMAIL))
				? htmlentities($_POST['newemail'])
				: false;
			}
			if(!empty($_POST['confirmemail'])){
				$confirmemail = (filter_var(htmlentities($_POST['confirmemail']), FILTER_SANITIZE_EMAIL))
				? htmlentities($_POST['confirmemail'])
				: false;
			}
			$password = (!empty($_POST['password']))
				? htmlentities($_POST['password'])
				: false;
		 }
		 // verification des parametres
		if ($newemail && $newemail != null && $newemail != ""
			&& $confirmemail && $confirmemail != null && $confirmemail != ""
				&& $password && $password != null && $password != ""){
			 // Confirmation du mot de passe
			 if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
				 $message["error"] = "Votre mot de passe n'est pas confirmé.";
				 return $message;
			} // endif password match
			 // Is email sent valid
			 elseif (filter_var($newemail, FILTER_VALIDATE_EMAIL) === false
			  && filter_var($confirmemail, FILTER_VALIDATE_EMAIL) === false) {  // Emails non valide
				$message["error"] = "L'email fourni n'est pas valide";
				return $message;
			  } // endif valid emails
			// Match newemail VS confirm email
			elseif($newemail != $confirmemail){ // emails do not match
				$message = "Votre nouvel email n'est pas confirmé";
				return $message["error"];
			} //endif match email
			// Testing newemail VS user current email 
			elseif($newemail == $this->session['user']->email()) { // newemail match with current email
				$message["error"] = "Votre nouvel email doit être différent de votre adresse mail actuelle";
				return $message;
			} //endif testing newemail VS currentemail
			else{
				// ALL PARAM OK
				$user = $this->session['user'];
				$user->setEmail($newemail);
				return $user;
			}
		}
		else{
			$message["error"]= "Paramêtres non valides";
			return $message;
		}
	}
	public function treatFormUser_phone(){
		$newphone = null;
		$confirmphone = null;
		$password = null;
		if(array_key_exists('newphone', $_POST)
		  && array_key_exists('confirmphone', $_POST)
			&& array_key_exists('password', $_POST)){
			$newphone = (!empty($_POST['newphone']))
				? htmlentities($_POST['newphone'])
				: false;
			$confirmphone = (!empty($_POST['confirmphone'])) 
				? htmlentities($_POST['confirmphone'])
				: false;
			$password = (!empty($_POST['password']))
				? htmlentities($_POST['password'])
				: false;
		  }
		 // Check param
		 if ($newphone && $newphone != null && $newphone != ""
		  && $confirmphone && $confirmphone != null && $confirmphone != ""
			&& $password && $password != null && $password != ""){
			 // Password match
			 if(!password_verify($password, $this->session['user']->password())){ // No match submitted password VS user's password
				$message["error"] = "Votre mot de passe n'est pas confirmé.";
				return $message;
			 }
			 // Valid phone
			 elseif(filter_var($newphone, FILTER_VALIDATE_REGEXP, array(
				"options" =>array("regexp"=>"#^0[1-9]([-. ]?[0-9]{2}){4}$#"))) === false) { // Phone non valide
					$message["error"] = "Le numero de téléphone fourni n'est pas valide";
					return $message;
			} // endif valid phone
			// match phones
			elseif($newphone != $confirmphone){ // no match
				$message["error"] = "Votre nouveau numero n'est pas confirmé";
				return $message;
			} // endif match phones
			else{
				// ALL PARAM OK
				$user = $this->session['user'];
				$user->setPhone($newphone);
				return $user;
			}
		}
		else{
			$message["error"]= "Paramêtres non valides";
			return $message;
		}
	}
	public function treatFormUser_password(){
		$currentpassword = null;
		$newpassword = null;
		$pwconfirm = null;
		if(array_key_exists('currentpassword', $_POST)
		  && array_key_exists('newpassword', $_POST)
			&& array_key_exists('pwconfirm', $_POST)){
			$currentpassword = (!empty($_POST['currentpassword']))
				? htmlentities($_POST['currentpassword'])
				: false;
			$newpassword = (!empty($_POST['newpassword']))
				? htmlentities($_POST['newpassword'])
				: false;
			$pwconfirm = (!empty($_POST['pwconfirm']))
				? htmlentities($_POST['pwconfirm'])
				: false;
		  }
		 // Check param
		 if ($currentpassword && $currentpassword != null && $currentpassword != ""
		  && $newpassword && $newpassword != null && $newpassword != ""
			&& $pwconfirm && $pwconfirm != null && $pwconfirm != ""){
			 // Password match
			 if(!password_verify($currentpassword, $this->session['user']->password())){ // No match submitted password VS user's password
					$message["error"] = "Mot de passe incorrect";
					return	$message;
			 }
			 // match new VS confirm
			 elseif($newpassword != $pwconfirm) { //no match
					$message["error"] = "Mot de passe non confirmé";
					return $message;
			}
			else{
				// ALL PARAM OK
				$password = password_hash($newpassword, PASSWORD_DEFAULT);
				$user = $this->session['user'];
				$user->setPassword($password);
				return $user;
			}
		}
		else{
			$message["error"]= "Paramêtres non valides";
			return $message;
		}
	}
	public function sendRequest(User $user, $str){
		
		//$www= "http://www.courtcircuit.bio/user/validation/".$customer->id()."/".$customer->email_code();
		$to= "contact@courtcircuit.bio";
		$subject= "[Court Circuit] Support : Mot de passe oublié";
		$message= "Mot de passe oublié! \n
					Compte : ".$user->email()." \n\n
					Nom : ".ucfirst($user->lastname())." \n
					Prénom : ".ucfirst($user->name())." \n
					Téléphone : ".$user->phone()." \n 
					Envoyer code par téléphone... \n\n
					
					CODE : ".$str." \n
					
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
	public function sendMail(User $user, $new){
		
		//$www= "http://www.courtcircuit.bio/user/validation/".$customer->id()."/".$customer->email_code();
		$to= $user->email();
		$subject= "[Court Circuit] Support : Mot de passe oublié";
		$message= "Cher utilisateur, \n\
					Suite à votre demande, votre mot de passe à été réinitialisé.
					
					Voici votre nouveau mot de passe : ".$new." \n\n
					
					Nous vous conseillons de modifier ce mot de passe rapidement.
					
					Si vous n'êtes pas à l'origine de cette action, veuillez nous contacter à l'adresse suivante : \n
					
					contact@courtcircuit.bio \n\n
					
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
	public function init(){
		$user= $this->session['user'];
		if(!$user) return false;
		else return $user;
	}
}
