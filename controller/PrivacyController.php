<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';

class PrivacyController extends AbstractController {
	/**
	 * @Route("/")
     * @Method("GET")
     */
   public function indexAction(){
	   $privacy= array();
	   $privacy["slogan"] = "Chez Court Circuit, agir en toute transparence est au coeur de nos préoccupations.";
	   $privacy["introduction"] = "Tout comme notre démarche permet d'être tout à fait transparent quant à la provenance et la qualité des produits,"
									." il convient d'être tout aussi transparent quant à l'utilisation que nous avons des données personnelles que vous partagez avec nous."
										." Cette page vise à vous fournir les informations nécessaires pour comprendre comment nous collectons, utilisons et protégeons vos données.";

		return parent::render("privacy/privacy.html",  array("privacy"=>$privacy));
   }
}