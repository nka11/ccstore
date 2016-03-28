<?php

// Le fichier traite le formulaire 'produit'.
// Vérifie que les données sont correctes.
// Edit la base de donnée
// Renvoie vers la page admin avec un message $formAnswer

	$action 	= (!empty($_POST['Enregistrer']))	?	$_POST['Enregistrer']			:	NULL;
	
	$id_p		= (isset($_POST['id_p']))			?	$_POST['id_p']					:	NULL;
	$e			=	false;
	
	$listAtt	= array('titre', 'prix_achat', 'prix_vente', 'tva', 'tag_cat', 'id_producteur', 'description', 'img');
	
	foreach($listAtt as $att){
		
		$$att 	= (!empty($_POST[$att]))	?	htmlentities($_POST[$att])	:	NULL;
		
		$e		= (!empty($$att))			?	FALSE						:	TRUE;
		
	}
	
	$is_active = (!empty($_POST['is_active'])) ?	true	:	false;
	
	if(!$e){
		
		$produit = new Produit (	array(	'id_p'			=>	$id_p,
											'titre'			=>	$titre,
											'prix_achat'	=>	$prix_achat,
											'prix_vente'	=>	$prix_vente,
											'tva'			=>	$tva,
											'id_producteur'	=>	$id_producteur,
											'tag_cat'		=>	$tag_cat,
											'description'	=>	$description,
											'is_active'		=>	$is_active,
											'img'			=>	$img));
		
		
		
		
		switch($action){
			
			case	'ajouter'	:	add_produit($produit); $formAnswer =	'Nouveau produit enregistré';
			break;
			case	'modifier'	:	set_produit($produit); $formAnswer =	'Produit modifié';
			break;			
			
		}

	
	header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}else{			// Si le formulaire comporte des erreurs (ici incomplet)
		
		$formAnswer =	'Formulaire incomplet';
		header('Location: admin.php?what='.$table.'&show=list&formAnswer='.$formAnswer);exit();
		
	}