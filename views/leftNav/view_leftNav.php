<?php ob_start();?>

	
		<div id='leftNav_logo'><a href='index.php'><img src='img/design/logo/logo_V8.png';/></a></div>
		<h1 id='leftNav_titre'>COURT-CIRCUIT</h1>
		<div id='leftNav_recherche'>
			<h3>Rechercher</h3>
			<div id='leftNav_rechercher'><input type='search' placeholder='Mot clé' name='rechercher'/><a href=''><img src='img/design/footer/loupe.png'/></a></div>
		</div>
		<ul id='leftNav_ul'>
			<li id='leftNav_boxUser'><?php echo $view_boxUser;?></li>
			<ul class='leftNav_li'><h3>Catégories</h3><?php echo $view_listCat;?></ul>
			<?php if($session_admin_open):?>
			<li class='leftNav_li'><?php echo $view_headBand;?></li>
			<?php else:?>
			<li class='leftNav_li'>
				<h3>A propos</h3>
				<ul id='leftNav_ul_about'>
					<li class='leftNav_li_about'><a href=''>Comment ça marche?</a></li>
					<li class='leftNav_li_about'><a href=''>Vos producteurs</a></li>
					<li class='leftNav_li_about'><a href=''>Notre équipe</a></li>
				</ul>
			</li>
			<?php endif;?>
		</ul>

<?php $view_leftNav	=	ob_get_clean();?>

<?php	// $view_panier n'est pas défini dans le cas d'une session administrateur. On échappe l'affichage en mode 'administrateur'.
		// A propos n'est pas affiché pour les administrateurs.