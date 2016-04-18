<?php 

/**
 * Traitement du formulaire d'inscription client.
 * Vérifie les données reçus
 * Retourne un rapport d'erreur dans array $error.
 * Si pas d'erreur, enregistre ou modifie le client en base de donnée Dolibarr.
 */

$action = (!empty($_POST['action'])) ? htmlentities($_POST['action']) : NULL;
$id_c = (isset($_POST['id_c'])) ? intval($_POST['id_c']) : NULL;
$listatt = array('nom_c', 'prenom_c', 'email_c','mdp_c', 'adresse_c', 'cp_c', 'ville_c', 'departement_c', 'telephone_c');
$errors = array();
foreach ($listatt as $att) {
	$$att = (!empty($_POST[$att])) ? htmlentities($_POST[$att]) : NULL;
	if(empty($$att))
		$errors[$att] = "Champs vide";
}
if(!empty($email_c)){
	if(!filter_var($email_c, FILTER_VALIDATE_EMAIL))
		$errors[$listatt[2]] = "Adresse email non valide";
}
elseif(!empty($telephone_c)){
	if(!filter_var($telephone_c, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"#^0[1-9](\s\-\.)?([0-9]{2}(\s\-\.)?){3}[0-9]{2}$#"))))
		$errors[$listatt[3]] = "Numéro de téléphone non valide";
}
elseif(!empty($cp_c)){
	if(!filter_var($cp_c, FILTER_VALIDATE_REGEXP,
		array("options"=>array("regexp"=>"#[0-9]{5}$#"))))
		$errors[$listatt[8]] = "Code postal non valide";
}
elseif(!empty($mdp_c)){
	$mdp_c = (!empty($mdp_c)) ? sha1(htmlentities($mdp_c)) : NULL;
}

/* SI PAS D'ERREUR */
if(count($errors)==0){
	$newclient = new Client (array(
		'id_c'			=>	$id_c,
		'nom'			=>	$nom_c,
		'prenom'		=>	$prenom_c,
		'email'			=>	$email_c,
		'mdp'			=>	$mdp_c,
		'adresse'		=>	$adresse_c,
		'code_postal'	=>	$cp_c,
		'ville'			=>	$ville_c,
		'departement'	=>	$departement_c,
		'telephone'		=>	$telephone_c
	));
	switch($action){
		case 'ajouter'	:	
			$client = $clientdao->createClient($newclient);
			$alert = "Votre inscription a bien été prise en compte. Un mail de confirmation a été envoyé à l'adresse suivante :". $client->email() .
			 "<br/>Merci de bien vouloir valider votre adresse email en cliquant sur le lien.";
		break;
		case 'modifier'	:	
			$client = $clientDAO->updateClient($newclient);
			$alert = "Vos modifications ont bien été prises en compte.";
		break;
	}
	if($client == false){
		$errors["objet"] = "Une erreur s'est produite lors de l'enregistrement";
	}
}