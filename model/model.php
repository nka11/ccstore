<?php
require_once "conf/database.cnf.default.php";
	//CONNEXION DATA BASE
		
	function getBdd() {
		global $db_string, $db_user, $db_password;
    $bdd = new PDO($db_string, $db_user, $db_password);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		
		return $bdd;
		
	}
	
	// GESTIONNAIRE DE PAGES
	
	function get_page($nomPage){
		
		//$bdd= getBdd();     --> On pourra mettre des page en base de donnée.
		
		$page = new Page(array(		'id_page'		=> 1,
									'titre'			=> $nomPage,
									'description'	=> 'Page test',
									'list_keywords'	=> 'test',
									'subject'		=> 'sujet test'));
		
		return $page;
	}
	
	// GESTIONNAIRE DES ADMINISTRATEURS
	
	function get_admin($login){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM admins WHERE login = :login');
		$q->bindValue(':login', $login);
		
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $admin = new Admin ($donnees);
				}
			return $admin;
			
		}else{ return NULL;}
		
		
	}
	
	// REQUEST TABLE = produits
	
	function get_produit($id_p) {
		
		$bdd = getBdd();
		
		$pro = $bdd->prepare('SELECT * FROM produits WHERE id_p = :id_p');
		$pro->bindValue(':id_p', $id_p, PDO::PARAM_INT);
		$pro->execute();
		
		if ($pro->rowCount() == 1){
			
			while ($donnees = $pro->fetch(PDO::FETCH_ASSOC))
				{
				  $produit = new Produit ($donnees);
				}
			return $produit;
			
		}else{ return NULL;}
	
	}
	
	function set_produit(Produit $produit){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE produits SET titre= :titre, prix_achat= :prix_achat, prix_vente= :prix_vente, tva= :tva, id_producteur= :id_producteur, id_cat= :id_cat, description= :description, is_active= :is_active, img= :img WHERE id_p = :id_p');
		// Assignation des valeurs à la requête.
		$q->bindValue(':titre', $produit->titre());
		$q->bindValue(':prix_achat', $produit->prix_achat());
		$q->bindValue(':prix_vente', $produit->prix_vente());
		$q->bindValue(':tva', $produit->tva());
		$q->bindValue(':id_producteur', $produit->id_producteur(), PDO::PARAM_INT);
		$q->bindValue('id_cat', $produit->id_cat(), PDO::PARAM_INT);
		$q->bindValue(':description', $produit->description());
		$q->bindValue(':is_active', $produit->is_active());
		$q->bindValue(':img', $produit->img());
		$q->bindValue(':id_p', $produit->id_p(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_produit(Produit $produit){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO produits SET titre= :titre, prix_achat= :prix_achat, prix_vente= :prix_vente, tva= :tva, id_producteur= :id_producteur, tag_cat= :tag_cat, description= :description, is_active= :is_active, img= :img');
		// Assignation des valeurs à la requête.
		$q->bindValue(':titre', $produit->titre());
		$q->bindValue(':prix_achat', $produit->prix_achat());
		$q->bindValue(':prix_vente', $produit->prix_vente());
		$q->bindValue(':tva', $produit->tva());
		$q->bindValue(':id_producteur', $produit->id_producteur());
		$q->bindValue(':tag_cat', $produit->categorie()->tag());
		$q->bindValue(':description', $produit->description());
		$q->bindValue(':is_active', $produit->is_active());
		$q->bindValue(':img', $produit->img());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_produit($categorie=NULL){
		
		$bdd = getBdd();
		
		if(empty($categorie)){
			
			$q = $bdd->prepare('SELECT * FROM produits');
			$q->execute();
			
			
		}else{
			
			$q = $bdd->prepare('SELECT * FROM produits WHERE tag_cat= :tag_cat');
			
			$q->bindValue(':tag_cat', $categorie->tag());
			$q->execute();
			
		}
		
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $produits[] = new Produit($donnees);
				}
				return $produits;
			}else{	return array();}
	}
	
	function delete_produit(Produit $produit){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM produits WHERE id_p = '.$produit->id_p());
		
	}
	
	//REQUEST TABLE= CATEGORIES
	
	function get_categorie($tag) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM categories WHERE tag = :tag');
		$q->bindValue(':tag', $tag);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $cat = new Categorie ($donnees);
				}
			return $cat;
			
		}else{ return NULL;}
	
	}
	
	function set_categorie(Categorie $categorie){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE categories SET id_parent= id_parent, tag= :tag WHERE id_cat = :id_cat');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_parent', $categorie->id_parent(), PDO::PARAM_INT);
		$q->bindValue(':tag', $categorie->tag());
		$q->bindValue(':id_cat', $categorie->id_cat(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_categorie(Categorie $categorie){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO categories SET id_parent= :id_parent, tag= :tag');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_parent', $categorie->id_parent(), PDO::PARAM_INT);
		$q->bindValue(':tag', $categorie->tag());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_categorie($categorie=NULL){
		
		$bdd = getBdd();
		
		if(empty($categorie)){
			
			$q = $bdd->prepare('SELECT * FROM categories');
			$q->execute();
			
			
		}else{
			
			$q = $bdd->prepare('SELECT * FROM categories WHERE id_parent= :id_parent');
			
			$q->bindValue(':id_parent', $categorie->id_cat(), PDO::PARAM_INT);
			$q->execute();
			
		}
		
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $categories[] = new Categorie($donnees);
				}
				return $categories;
			}else{	return array();}
	}
	
	function delete_categorie(Categorie $categorie){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM categories WHERE id_cat = '.$categorie->id_cat());
		
	}
	
	//REQUEST TABLE= PANIER
	
	function get_panier($id_pa) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM paniers WHERE id_pa = :id_pa');
		$q->bindValue(':id_pa', $id_pa, PDO::PARAM_INT);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $panier = new Panier ($donnees);
				}
			return $panier;
			
		}else{ return NULL;}
	
	}
	
	function set_panier(Panier $panier){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE paniers SET id_c= :id_c, date_crea_pa= NOW() WHERE id_pa = :id_pa');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $panier->id_c(), PDO::PARAM_INT);
		//$q->bindValue(':date_crea', date());
		$q->bindValue(':montant', $panier->montant());
		$q->bindValue(':id_com', $panier->id_pa(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_panierOnly(Panier $panier){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO paniers SET id_c= :id_c, date_crea_pa= NOW()');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $panier->id_c(), PDO::PARAM_INT);
		//$q->bindValue(':date_crea', $panier->date_crea());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	
	function add_panier(Panier $panier){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO paniers SET id_c= :id_c, date_crea_pa= NOW()');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $panier->id_c(), PDO::PARAM_INT);
		//$q->bindValue(':date_crea', $panier->date_crea());
		
		// Exécution de la requête.
		$q->execute();
		
		foreach( $panier->list_lc() as $lc){
		$p = $bdd->prepare('INSERT INTO lignes_commande SET id_pa= :id_pa, id_p= :id_p, quantite= :quantite');
		$p->bindValue('id_pa', $panier->id_pa(), PDO::PARAM_INT);
		$p->bindValue('id_p', $lc->id_p(), PDO::PARAM_INT);
		$p->bindValue('quantite', $lc->quantite(), PDO::PARAM_INT);
		$p->execute();}
		
	}
	
	function getList_panier(){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM paniers');
		$q->execute();
					
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $paniers[] = new Panier($donnees);
				}
				return $paniers;
			}else{	return array();}
	}
	
	function delete_panier(Panier $panier){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM paniers WHERE id_pa = '.$panier->id_pa());
		
		foreach( $panier->list_lc() as $lc){
			
			$bdd->exec('DELETE FROM lignes_commande WHERE id_pa = '.$lc->id_pa());
			
		}
		
	}
	
	//REQUEST TABLE= COMMANDES
	
	function get_commande($id_com) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM commandes WHERE id_com = :id_com');
		$q->bindValue(':id_com', $id_com, PDO::PARAM_INT);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $com = new Commande ($donnees);
				}
			return $com;
			
		}else{ return NULL;}
	
	}
	
	function set_commande(Commande $commande){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE commandes SET id_c= :id_c, date_crea_com = NOW(), date_liv= :date_liv, list_prod= :list_prod, mode_liv= :mode_liv, total= :total, commentaire = :commentaire, statut= :statut WHERE id_com = :id_com');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $commande->client()->id_c(), PDO::PARAM_INT);
		//$q->bindValue(':date_crea_com', date());
		$q->bindValue(':date_liv', $commande->date_liv());
		$q->bindValue(':list_prod', $commande->list_prod());
		$q->bindValue(':mode_liv', $commande->mode_liv());
		$q->bindValue(':total', $commande->montant());
		$q->bindValue(':statut', $commande->statut());
		$q->bindValue(':id_com', $commande->id_com(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_commande(Commande $commande){
		
		//echo 'je suis dans la fn : add_commande';
		//echo '<br/> je rentre les valeurs suivantes : <br/>';
		//echo $commande->id_pa();
		//echo '<br/>'.$commande->id_c();
		//echo '<br/>'.$commande->mode_liv();
		//echo '<br/>'.$commande->mode_paiement();
		//echo '<br/>'.$commande->calculTotal();
		//echo '<br/>'.$commande->commentaire();
		//echo '<br/>'.$commande->statut();exit();
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO commandes SET id_pa= :id_pa, id_c= :id_c, date_crea_com= NOW(), date_liv= NOW(), mode_liv= :mode_liv, mode_paiement= :mode_paiement, total= :total, commentaire= :commentaire, statut= :statut');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_pa', $commande->id_pa(), PDO::PARAM_INT);
		$q->bindValue(':id_c', $commande->id_c(), PDO::PARAM_INT);
		$q->bindValue(':mode_liv', $commande->mode_liv());
		$q->bindValue(':mode_paiement', $commande->mode_paiement());
		$q->bindValue(':total', $commande->calculTotal());
		$q->bindValue(':commentaire', $commande->commentaire());
		$q->bindValue(':statut', $commande->statut());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_commande(){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM commandes');
		$q->execute();
					
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $commandes[] = new Commande($donnees);
				}
				return $commandes;
			}else{	return array();}
	}
	
	function delete_commande(Commande $commande){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM commandes WHERE id_com = '.$commande->id_com());
		
	}
	
	// REQUEST TABLE = clients
	
	function get_clientByEmail($email){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM clients WHERE email = :email');
		$q->bindValue(':email', $email);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $client = new Client ($donnees);
				}
			return $client;
			
		}else{ return NULL;}
		
		
	}
	
	function get_client($id_c) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM clients WHERE id_c = :id_c');
		$q->bindValue(':id_c', $id_c, PDO::PARAM_INT);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $client = new Client ($donnees);
				}
			return $client;
			
		}else{ return NULL;}
	
	}
	
	function set_client(Client $client){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE clients SET nom= :nom, prenom= :prenom, email= :email, adresse= :adresse, code_postal= :cp_c, ville= :ville, departement= :departement, telephone= :telephone WHERE id_c = :id_c');
		// Assignation des valeurs à la requête.
		$q->bindValue(':nom', $client->nom());
		$q->bindValue(':prenom', $client->prenom());
		$q->bindValue(':email', $client->email());
		$q->bindValue(':adresse', $client->adresse());
		$q->bindValue(':cp_c', $client->code_postal());
		$q->bindValue(':ville', $client->ville());
		$q->bindValue(':departement', $client->departement());
		$q->bindValue(':telephone', $client->telephone());
		$q->bindValue(':id_c', $client->id_c(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_client(Client $client){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO clients SET nom= :nom, prenom= :prenom, email= :email, mdp= :mdp, adresse= :adresse, code_postal= :cp_c, ville= :ville, departement= :departement, telephone= :telephone');
		// Assignation des valeurs à la requête.
		$q->bindValue(':nom', $client->nom());
		$q->bindValue(':prenom', $client->prenom());
		$q->bindValue(':email', $client->email());
		$q->bindValue(':mdp', $client->mdp());
		$q->bindValue(':adresse', $client->adresse());
		$q->bindValue(':cp_c', $client->code_postal());
		$q->bindValue(':ville', $client->ville());
		$q->bindValue(':departement', $client->departement());
		$q->bindValue(':telephone', $client->telephone());
				
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_client(){
		
		$bdd = getBdd();
		
				
		$q = $bdd->prepare('SELECT * FROM clients');
		$q->execute();
		
		
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $clients[] = new Client($donnees);
				}
				return $clients;
			}else{	return array();}
	}
	
	function delete_client(Client $client){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM clients WHERE id_c = '.$client->id_c());
		
	}
	
	// REQUEST TABLE = producteurs
	
	function get_producteur($id_pro) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM producteurs WHERE id_pro = :id_pro');
		$q->bindValue(':id_pro', $id_pro, PDO::PARAM_INT);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $producteur = new Producteur ($donnees);
				}
			return $producteur;
			
		}else{ return NULL;}
	
	}
	
	function set_producteur(Producteur $producteur){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE producteurs SET denom= :denom, titre= :titre, adresse= :adresse, departement= :departement, telephone= :telephone, description= :description WHERE id_pro = :id_pro');
		// Assignation des valeurs à la requête.
		$q->bindValue(':denom', $producteur->denom());
		$q->bindValue(':titre', $producteur->titre());
		$q->bindValue(':adresse', $producteur->adresse());
		$q->bindValue(':departement', $producteur->departement());
		$q->bindValue(':telephone', $producteur->telephone());
		$q->bindValue(':description', $producteur->description());
		$q->bindValue(':id_pro', $producteur->id_pro(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_producteur(Producteur $producteur){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO producteurs SET denom= :denom, titre= :titre, adresse= :adresse, departement= :departement, telephone= :telephone, description= :description');
		// Assignation des valeurs à la requête.
		$q->bindValue(':denom', $producteur->denom());
		$q->bindValue(':titre', $producteur->titre());
		$q->bindValue(':adresse', $producteur->adresse());
		$q->bindValue(':departement', $producteur->departement());
		$q->bindValue(':telephone', $producteur->telephone());
		$q->bindValue(':description', $producteur->description());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_producteur(){
		
		$bdd = getBdd();
			
		$q = $bdd->prepare('SELECT * FROM producteurs');
		$q->execute();
		
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $producteurs[] = new Producteur($donnees);
				}
				return $producteurs;
			}else{	return array();}
	}
	
	function delete_producteur(Producteur $producteur){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM producteurs WHERE id_pro = '.$producteur->id_pro());
		
	}
	
	//REQUEST TABLE= CATEGORIES
	
	function get_adhesion($id_a) {
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('SELECT * FROM adhesions WHERE id_a = :id_a');
		$q->bindValue(':id_a', $id_a, PDO::PARAM_INT);
		$q->execute();
		
		if ($q->rowCount() == 1){
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $adh = new Adhesion ($donnees);
				}
			return $adh;
			
		}else{ return NULL;}
	
	}
	
	function set_adhesion(Adhesion $adhesion){
		
		$bdd = getBdd();
		// Prépare une requête de type UPDATE.
		$q = $bdd->prepare('UPDATE adhesions SET id_c = :id_c, year= :year, date_paiement= :date_paiement WHERE id_a = :id_a');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $adhesion->adh()->id_c(), PDO::PARAM_INT);
		$q->bindValue(':year', $adhesion->year());
		$q->bindValue(':date_paiement', $adhesion->date_paiement());
		$q->bindValue(':id_a', $adhesion->id_a(), PDO::PARAM_INT);
		
		// Exécution de la requête.
		$q->execute();
	}
	
	function add_adhesion(Adhesion $adhesion){
		
		$bdd = getBdd();
		
		$q = $bdd->prepare('INSERT INTO adhesions SET id_c = :id_c, year= :year, date_paiement= :date_paiement');
		// Assignation des valeurs à la requête.
		$q->bindValue(':id_c', $adhesion->adh()->id_c(), PDO::PARAM_INT);
		$q->bindValue(':year', $adhesion->year());
		$q->bindValue(':date_paiement', $adhesion->date_paiement());
		
		// Exécution de la requête.
		$q->execute();
		
	}
	
	function getList_adhesion($year=NULL){
		
		$bdd = getBdd();
		
		if(empty($adhesion)){
			
			$q = $bdd->prepare('SELECT * FROM adhesions');
			$q->execute();
			
			
		}else{
			
			$q = $bdd->prepare('SELECT * FROM adhesions WHERE year= :year');
			
			$q->bindValue(':year', $year, PDO::PARAM_INT);
			$q->execute();
			
		}
		
		if ($q->rowCount() > 0){
				while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
				{
				  $adhesions[] = new Adhesion($donnees);
				}
				return $adhesions;
			}else{	return array();}
	}
	
	function delete_adhesion(Adhesion $adhesion){
		
		$bdd = getBdd();
		$bdd->exec('DELETE FROM adhesions WHERE id_cat = '.$adhesion->id_a());
		
	}
