<?php

// Le fichier traite le formulaire 'categorie'.
// Vérifie que les données sont correctes.
// Edit la base de donnée
// Renvoie vers la page admin avec un message $formAnswer

	$action 	= (!empty($_POST['Enregistrer']))	?	$_POST['Enregistrer']			:	NULL;
	
	$id_cat		= (isset($_POST['id_cat']))			?	$_POST['id_cat']					:	NULL;
	$e			=	false;
	
	$listAtt	= array('tag');
	
	foreach($listAtt as $att){
		
		$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;
		
		$e		= (!empty($$att))			?	FALSE						:	TRUE;
		
	}
	
	if(!$e){
		
		$categorie = new Categorie (	array(	'id_cat'		=>	$id_cat,
												'id_parent'		=>	$id_parent,
												'tag'			=>	$tag));
		
		
		switch($action){
			
			case	'ajouter'	:	add_categorie($categorie); $formAnswer =	'Nouvelle catégorie enregistrée';
			break;
			case	'modifier'	:	set_categorie($categorie); $formAnswer =	'Catégorie modifiée';
			break;			
			
		}

	
	header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}else{			// Si le formulaire comporte des erreurs (ici incomplet)
		
		$formAnswer =	'Formulaire incomplet';
		header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}