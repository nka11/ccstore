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
      $cldao = new CustomerDAO();
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
		 $nom = null;
		 $prenom = null;
		 $adress = null;
		 $code = null;
		 $town = null;
		 $phonenumber = null;
		 if (array_key_exists('HTTP_CONTENT_TYPE',$_SERVER)
		  && $_SERVER['HTTP_CONTENT_TYPE'] == "application/json") {
		  $postRequest = json_decode(stream_get_contents(STDIN));
		  $email = $postRequest->email;
		  $password = $postRequest->password;
		  $pwconfirm = $postRequest->pwconfirm;
		  $nom = $postRequest->nom;
		  $prenom = $postRequest->prenom;
		  $adress = $postRequest->adress;
		  $code = $postRequest->code;
		  $town = $postRequest->town;
		  $phonenumber =  $postRequest->phonenumber;
		}
		if(array_key_exists('email', $_REQUEST)
			&& array_key_exists('password', $_REQUEST)
			&& array_key_exists('pwconfirm', $_REQUEST)
			&& array_key_exists('nom', $_REQUEST)
			&& array_key_exists('prenom', $_REQUEST)
			&& array_key_exists('adress', $_REQUEST)
			&& array_key_exists('code', $_REQUEST)
			&& array_key_exists('town', $_REQUEST)
			&& array_key_exists('phonenumber', $_REQUEST)){
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];
			$pwconfirm = $_REQUEST['pwconfirm'];
			$nom = $_REQUEST['nom'];
			$prenom = $_REQUEST['prenom'];
			$adress = $_REQUEST['adress'];
			$code = $_REQUEST['code'];
			$town = $_REQUEST['town'];
			$phonenumber = $_REQUEST['phonenumber'];
		}
		// verification des parametres
		if ($email != null && $email != "" 
		 && $password != null && $password != ""
		 && $pwconfirm != null && $pwconfirm != ""
		 && $nom != null && $nom != ""
		 && $prenom != null && $prenom != ""
		 && $adress !=  null && $adress != ""
		 && $code != null && $code != ""
		 && $town != null && $town != ""
		 && $phonenumber != null && $phonenumber != "") {
		$cldao = new ClientDAO();
		}
	}
}
