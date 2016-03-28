<?php

// Le fichier traite le formulaire 'producteur'.
// Vérifie que les données sont correctes.
// Edit la base de donnée
// Renvoie vers la page admin avec un message $formAnswer

	$action 	= (!empty($_POST['Enregistrer']))	?	$_POST['Enregistrer']			:	NULL;
	
	$id_pro		= (isset($_POST['id_pro']))			?	$_POST['id_pro']					:	NULL;
	$e			=	false;
	
	$listAtt	= array('denom', 'titre', 'adresse', 'departement', 'telephone', 'description');
	
	foreach($listAtt as $att){
		
		$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;		
		$e		= (!empty($$att))			?	FALSE						:	TRUE;
		
	}

	if(!$e){
		
		$producteur = new Producteur (	array(	'id_pro'		=>	$id_pro,
												'denom'			=>	$denom,
												'titre'			=>	$titre,
												'adresse'		=>	$adresse,
												'departement'	=>	$departement,
												'telephone'		=>	$telephone,
												'description'	=>	$description));
		
		switch($action){
			
			case	'ajouter'	:	add_producteur($producteur); $formAnswer =	'Nouveau producteur enregistré';
			break;
			case	'modifier'	:	set_producteur($producteur); $formAnswer =	'Producteur modifié';
			break;			
			
		}

	
	header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}else{			// Si le formulaire comporte des erreurs (ici incomplet)
		
		$formAnswer =	'Formulaire incomplet';
		header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}