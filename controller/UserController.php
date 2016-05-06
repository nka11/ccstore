<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ClientDAO.php';
class UserController extends AbstractController {
  /**
   * Index /user/, shows user profile
   *
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    return parent::render('user.html');
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
      $cldao = new ClientDAO();
      try {
        $client = $cldao->getClientByEmail($email);
        $client->setMdp($password);
        $client = $cldao->login($client);
        if ($client && $client->api_key() != null && $client->api_key() != "") {
          // Login success
          $_SESSION['statut'] = 'client';
          $_SESSION['user'] = $client;
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
}
