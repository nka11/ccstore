<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
require_once './model/ProduitDAO.php';
class InscriptionController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    $pdao = new ProduitDAO();
    $produits = $pdao->getProduits();
    return parent::render('inscription.html', array("products" => $produits));
  }
	/**
	 * Post method
	 * 
	 * @Route("/post")
	 */
	/* EN REDACTION
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
		}
	 }
	 */
}