<?php ob_start();?>

<div id='div_footerIndexContainer'>
	<ul class='ul_footerIndex'>
		<h3>A propos</h3>
		<li><a href=''>Comment ça marche?</a></li>
		<li><a href=''>Vos producteurs</a></li>
		<li><a href=''>Notre équipe</a></li>
	</ul>
	<ul class='ul_footerIndex'>
		<h3>Catégories</h3>
		<?php echo $view_listCat;?>
	</ul>
	<ul class='ul_footerIndex'>
		<h3>Rechercher</h3>
		<div id='rechercher'><input type='search' placeholder='Mot clé' name='rechercher'/><a href=''><img src='img/design/footer/loupe.png'/></a></div>
	</ul>
<?php if(!$session_admin_open):?>
	<div id='padlock'><a href='admin.php'><img src='img/design/padlock.png'/></a></div>
<?php endif;?>
</div>
<?php $view_footer	=	ob_get_clean();?>